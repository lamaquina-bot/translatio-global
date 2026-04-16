// Cloudflare Worker: Contact form + Lead capture API

interface Env {
  DB: D1Database;
}

export const onRequestPost: PagesFunction<Env> = async (context) => {
  const { request, env } = context;
  const body = await request.json() as {
    name: string;
    email: string;
    country?: string;
    message?: string;
    language?: string;
    source?: string;
    gdpr_consent?: boolean;
  };

  const { name, email, country = '', message = '', language = 'es', source = 'form', gdpr_consent = false } = body;

  // Validation
  if (!name || name.length < 2 || name.length > 100) {
    return new Response(JSON.stringify({ error: 'Invalid name' }), { status: 400 });
  }
  if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    return new Response(JSON.stringify({ error: 'Invalid email' }), { status: 400 });
  }
  if (!gdpr_consent) {
    return new Response(JSON.stringify({ error: 'GDPR consent required' }), { status: 400 });
  }

  // Check rate limit (simple: count leads from this email today)
  try {
    const today = new Date().toISOString().split('T')[0];
    const count = await env.DB.prepare(
      "SELECT COUNT(*) as c FROM leads WHERE email = ? AND created_at > ?"
    ).bind(email, today).first();

    if ((count as any)?.c >= 3) {
      return new Response(JSON.stringify({ error: 'Rate limit exceeded' }), { status: 429 });
    }
  } catch {}

  // Insert lead
  try {
    await env.DB.prepare(
      `INSERT INTO leads (name, email, country, language, source, first_question, gdpr_consent, status)
       VALUES (?, ?, ?, ?, ?, ?, ?, 'new')`
    ).bind(name, email, country, language, source, message, gdpr_consent ? 1 : 0).run();
  } catch (err: any) {
    return new Response(JSON.stringify({ error: 'Database error', detail: err.message }), { status: 500 });
  }

  // TODO: Send email notification via Resend/SendGrid
  // For now, data is stored in D1 and can be queried later

  return new Response(JSON.stringify({
    success: true,
    message: 'Lead captured successfully',
  }), {
    status: 201,
    headers: { 'Content-Type': 'application/json' },
  });
};
