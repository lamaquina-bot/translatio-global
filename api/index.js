const express = require('express');
const cors = require('cors');
const rateLimit = require('express-rate-limit');
const fs = require('fs');
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;
const DATA_DIR = process.env.DATA_DIR || './data';
const SUBMISSIONS_FILE = path.join(DATA_DIR, 'submissions.json');

// Ensure data directory exists
if (!fs.existsSync(DATA_DIR)) {
  fs.mkdirSync(DATA_DIR, { recursive: true });
}

// CORS - allow Translatio domains
app.use(cors({
  origin: [
    'https://translatio.thefuckinggoat.cloud',
    'https://translatio-global.thefuckinggoat.cloud',
    /^https:\/\/[a-z]+\.translatio\.thefuckinggoat\.cloud$/,
    'http://localhost:3001', // dev
  ],
  methods: ['GET', 'POST', 'OPTIONS'],
  allowedHeaders: ['Content-Type'],
}));

app.use(express.json());

// Rate limiting
const contactLimiter = rateLimit({
  windowMs: 15 * 60 * 1000, // 15 min
  max: 10,
  message: { error: 'Too many requests. Please try again later.' },
  standardHeaders: true,
  legacyHeaders: false,
});

const chatLimiter = rateLimit({
  windowMs: 15 * 60 * 1000,
  max: 20,
  message: { error: 'Too many requests. Please try again later.' },
  standardHeaders: true,
  legacyHeaders: false,
});

// Helpers
function validateEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function loadSubmissions() {
  try {
    if (fs.existsSync(SUBMISSIONS_FILE)) {
      return JSON.parse(fs.readFileSync(SUBMISSIONS_FILE, 'utf8'));
    }
  } catch (e) { /* ignore */ }
  return [];
}

function saveSubmission(entry) {
  const submissions = loadSubmissions();
  submissions.push(entry);
  fs.writeFileSync(SUBMISSIONS_FILE, JSON.stringify(submissions, null, 2));
}

// Email notification stub - configure with env vars later
async function sendNotification(entry) {
  const smtpConfig = {
    host: process.env.SMTP_HOST,
    port: process.env.SMTP_PORT,
    user: process.env.SMTP_USER,
    pass: process.env.SMTP_PASS,
    to: process.env.NOTIFY_EMAIL || 'admin@translatio.com',
  };
  // TODO: Implement with nodemailer when SMTP credentials are provided
  if (!smtpConfig.host) return;
  console.log(`[EMAIL] Would notify ${smtpConfig.to} about new ${entry.type} submission from ${entry.email}`);
}

// Health check
app.get('/health', (req, res) => {
  res.json({ status: 'ok', timestamp: new Date().toISOString(), service: 'translatio-contact-api' });
});

// Contact form
app.post('/api/contact', contactLimiter, (req, res) => {
  const { name, email, message, language, gdpr_consent, country } = req.body;

  // Validation
  if (!name || !email) {
    return res.status(400).json({ error: 'Name and email are required.' });
  }
  if (!validateEmail(email)) {
    return res.status(400).json({ error: 'Invalid email format.' });
  }
  if (!gdpr_consent) {
    return res.status(400).json({ error: 'GDPR consent is required.' });
  }

  const entry = {
    id: Date.now().toString(36) + Math.random().toString(36).slice(2, 7),
    type: 'contact',
    name: name.trim(),
    email: email.trim().toLowerCase(),
    country: (country || '').trim(),
    message: (message || '').trim(),
    language: language || 'es',
    gdpr_consent: true,
    timestamp: new Date().toISOString(),
    ip: req.headers['x-forwarded-for'] || req.socket.remoteAddress,
  };

  saveSubmission(entry);
  sendNotification(entry).catch(() => {});

  console.log(`[CONTACT] ${entry.name} <${entry.email}> (${entry.language})`);
  res.status(201).json({ success: true, message: 'Thank you! We will contact you within 24 hours.' });
});

// Chatbot lead
app.post('/api/chat', chatLimiter, (req, res) => {
  const { name, email, language, source, gdpr_consent } = req.body;

  if (!name || !email) {
    return res.status(400).json({ error: 'Name and email are required.' });
  }
  if (!validateEmail(email)) {
    return res.status(400).json({ error: 'Invalid email format.' });
  }
  if (!gdpr_consent) {
    return res.status(400).json({ error: 'GDPR consent is required.' });
  }

  const entry = {
    id: Date.now().toString(36) + Math.random().toString(36).slice(2, 7),
    type: 'chat_lead',
    name: name.trim(),
    email: email.trim().toLowerCase(),
    language: language || 'es',
    source: source || 'chatbot',
    gdpr_consent: true,
    timestamp: new Date().toISOString(),
    ip: req.headers['x-forwarded-for'] || req.socket.remoteAddress,
  };

  saveSubmission(entry);
  sendNotification(entry).catch(() => {});

  console.log(`[CHAT LEAD] ${entry.name} <${entry.email}> (${entry.language}) from ${entry.source}`);
  res.status(201).json({ success: true, message: 'Thank you! A specialist will contact you within 24 hours.' });
});

app.listen(PORT, () => {
  console.log(`Translatio Contact API running on port ${PORT}`);
});
