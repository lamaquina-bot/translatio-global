-- Translatio Global - Cloudflare D1 Schema
-- Version: 1.0

-- Leads capturados via formulario y chatbot
CREATE TABLE IF NOT EXISTS leads (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  email TEXT NOT NULL,
  country TEXT,
  language TEXT DEFAULT 'es',
  source TEXT DEFAULT 'form' CHECK(source IN ('form', 'chatbot', 'whatsapp')),
  source_page TEXT,
  first_question TEXT,
  status TEXT DEFAULT 'new' CHECK(status IN ('new', 'contacted', 'qualified', 'closed', 'spam')),
  gdpr_consent INTEGER DEFAULT 0,
  created_at TEXT DEFAULT (datetime('now')),
  contacted_at TEXT,
  notes TEXT
);

CREATE INDEX idx_leads_status ON leads(status);
CREATE INDEX idx_leads_email ON leads(email);
CREATE INDEX idx_leads_created ON leads(created_at);

-- Log de conversaciones del chatbot
CREATE TABLE IF NOT EXISTS chatbot_sessions (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  session_id TEXT NOT NULL,
  language TEXT DEFAULT 'es',
  started_at TEXT DEFAULT (datetime('now')),
  lead_captured INTEGER DEFAULT 0,
  escalated INTEGER DEFAULT 0
);

CREATE TABLE IF NOT EXISTS chatbot_messages (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  session_id TEXT NOT NULL,
  role TEXT NOT NULL CHECK(role IN ('user', 'assistant', 'system')),
  content TEXT NOT NULL,
  created_at TEXT DEFAULT (datetime('now'))
);

CREATE INDEX idx_chatbot_sessions ON chatbot_sessions(session_id);
CREATE INDEX idx_chatbot_messages_session ON chatbot_messages(session_id);

-- FAQ base (editable via admin en futuro)
CREATE TABLE IF NOT EXISTS faq (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  question_es TEXT NOT NULL,
  answer_es TEXT NOT NULL,
  question_en TEXT,
  answer_en TEXT,
  question_pt TEXT,
  answer_pt TEXT,
  question_zh TEXT,
  answer_zh TEXT,
  question_fr TEXT,
  answer_fr TEXT,
  keywords TEXT,
  category TEXT,
  sort_order INTEGER DEFAULT 0,
  active INTEGER DEFAULT 1
);
