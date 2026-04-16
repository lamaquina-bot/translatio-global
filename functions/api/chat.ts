// Cloudflare Worker: Chatbot API endpoint
// Handles FAQ matching and chatbot conversations

interface Env {
  DB: D1Database;
  AI: any;
}

const faqData: Record<string, Record<string, { q: string; a: string; keywords: string[] }>> = {
  es: {
    what_is: { q: '¿Qué es la subrogación gestacional?', a: 'La subrogación gestacional es un proceso donde una mujer (gestante) lleva el embarazo de personas que no pueden concebir por sí mismas. En Colombia, este proceso está regulado y Translatio acompaña tanto a padres como a gestantes durante todo el camino.', keywords: ['subrogación', 'gestacional', 'qué es', 'que es', 'como funciona', 'definición'] },
    legal: { q: '¿Es legal en Colombia?', a: 'Sí, la subrogación gestacional tiene un marco legal en Colombia. Translatio cuenta con equipo legal especializado que garantiza que todo el proceso cumpla con la normativa colombiana, protegiendo a todas las partes involucradas.', keywords: ['legal', 'ley', 'regulación', 'regulacion', 'permitido', 'colombia'] },
    cost: { q: '¿Cuánto cuesta?', a: 'Los costos dependen de cada caso particular. Te recomendamos solicitar una consulta gratuita donde evaluaremos tu situación y te proporcionaremos información detallada.', keywords: ['costo', 'precio', 'cuánto', 'cuanto', 'dinero', 'valor', 'tarifa'], escalate: true },
    time: { q: '¿Cuánto tiempo toma?', a: 'El tiempo varía según cada caso, pero generalmente el proceso completo puede tomar entre 12 y 24 meses. En tu consulta inicial te daremos una estimación más precisa.', keywords: ['tiempo', 'cuánto tiempo', 'duración', 'meses', 'años'] },
    protect: { q: '¿Cómo se protege a la gestante?', a: 'La gestante recibe protección legal completa, acompañamiento médico y psicológico durante todo el proceso. En Translatio, la gestante es usuaria del servicio, no un recurso. Su bienestar es prioridad.', keywords: ['gestante', 'protección', 'proteger', 'seguridad', 'derechos'] },
    requirements: { q: '¿Qué requisitos hay?', a: 'Los requisitos varían según el caso. Generalmente se necesita evaluación médica, legal y psicológica de todas las partes. En tu consulta inicial te explicaremos los requisitos específicos para tu situación.', keywords: ['requisitos', 'necesito', 'documentos', 'pedir', 'tramites'] },
  },
  en: {
    what_is: { q: 'What is gestational surrogacy?', a: 'Gestational surrogacy is a process where a woman (carrier) carries a pregnancy for people who cannot conceive on their own. In Colombia, this process is regulated and Translatio supports both parents and carriers.', keywords: ['surrogacy', 'what is', 'how does', 'definition', 'mean'] },
    legal: { q: 'Is it legal in Colombia?', a: 'Yes, gestational surrogacy has a legal framework in Colombia. Translatio has a specialized legal team ensuring full compliance with Colombian regulations.', keywords: ['legal', 'law', 'regulation', 'allowed', 'colombia'] },
    cost: { q: 'How much does it cost?', a: 'Costs depend on each case. We recommend requesting a free consultation for detailed information.', keywords: ['cost', 'price', 'how much', 'money', 'fee'], escalate: true },
    time: { q: 'How long does it take?', a: 'The complete process typically takes 12-24 months depending on each case.', keywords: ['time', 'how long', 'duration', 'months'] },
    protect: { q: 'How is the carrier protected?', a: 'The carrier receives complete legal protection, medical and psychological support. At Translatio, the carrier is a user of the service, not a resource.', keywords: ['carrier', 'protect', 'safety', 'rights'] },
    requirements: { q: 'What are the requirements?', a: 'Requirements vary by case. Generally medical, legal and psychological evaluations are needed.', keywords: ['requirements', 'need', 'documents'] },
  },
  pt: {
    what_is: { q: 'O que é substituição gestacional?', a: 'A substituição gestacional é um processo em que uma mulher (gestante) leva a gravidez para pessoas que não podem conceber por si mesmas. Na Colômbia, este processo é regulamentado.', keywords: ['substituição', 'gestacional', 'o que é', 'como funciona'] },
    legal: { q: 'É legal na Colômbia?', a: 'Sim, a substituição gestacional tem marco legal na Colômbia. A Translatio tem equipe jurídica especializada.', keywords: ['legal', 'lei', 'regulamentação', 'colômbia'] },
    cost: { q: 'Quanto custa?', a: 'Os custos dependem de cada caso. Recomendamos solicitar uma consulta gratuita.', keywords: ['custo', 'preço', 'quanto', 'dinheiro'], escalate: true },
  },
  zh: {
    what_is: { q: '什么是代孕？', a: '代孕是一种由女性（代孕妈妈）为无法自行受孕的人怀孕的过程。在哥伦比亚，这一过程受到监管。', keywords: ['代孕', '什么是', '如何'] },
    legal: { q: '哥伦比亚合法吗？', a: '是的，哥伦比亚有代孕的法律框架。Translatio有专业法律团队。', keywords: ['合法', '法律', '哥伦比亚'] },
    cost: { q: '费用是多少？', a: '费用因个案而异。建议申请免费咨询。', keywords: ['费用', '价格', '多少钱'], escalate: true },
  },
  fr: {
    what_is: { q: "Qu'est-ce que la GPA ?", a: "La gestation pour autrui est un processus où une femme porte une grossesse pour des personnes qui ne peuvent pas concevoir.", keywords: ['gpa', 'gestation', 'qu\'est-ce'] },
    legal: { q: 'Est-ce légal en Colombie ?', a: 'Oui, la GPA a un cadre juridique en Colombie. Translatio a une équipe juridique spécialisée.', keywords: ['légal', 'loi', 'colombie'] },
    cost: { q: 'Combien ça coûte ?', a: 'Les coûts dépendent de chaque cas. Nous recommandons une consultation gratuite.', keywords: ['coût', 'prix', 'combien'], escalate: true },
  },
};

function matchFaq(message: string, lang: string): { answer: string; escalate: boolean } | null {
  const faqs = faqData[lang] || faqData.es;
  const msgLower = message.toLowerCase();

  for (const [, faq] of Object.entries(faqs)) {
    if (faq.keywords.some(kw => msgLower.includes(kw))) {
      return { answer: faq.a, escalate: !!(faq as any).escalate };
    }
  }
  return null;
}

export const onRequestPost: PagesFunction<Env> = async (context) => {
  const { request, env } = context;
  const body = await request.json() as { session_id: string; language: string; message: string };
  const { session_id, language = 'es', message } = body;

  if (!message || message.length > 500) {
    return new Response(JSON.stringify({ error: 'Invalid message' }), { status: 400 });
  }

  // Try FAQ match first
  const faq = matchFaq(message, language);

  // Log message to D1 (if available)
  try {
    await env.DB.prepare(
      'INSERT INTO chatbot_messages (session_id, role, content) VALUES (?, ?, ?)'
    ).bind(session_id, 'user', message).run();
  } catch {}

  if (faq) {
    // Log bot response
    try {
      await env.DB.prepare(
        'INSERT INTO chatbot_messages (session_id, role, content) VALUES (?, ?, ?)'
      ).bind(session_id, 'assistant', faq.answer).run();
    } catch {}

    return new Response(JSON.stringify({
      type: 'answer',
      answer: faq.answer,
      escalate: faq.escalate,
    }), {
      headers: { 'Content-Type': 'application/json' },
    });
  }

  // No FAQ match → try Workers AI if available
  try {
    if (env.AI) {
      const aiResponse = await env.AI.run('@cf/meta/llama-3.1-8b-instruct', {
        messages: [
          { role: 'system', content: `Eres el asistente de Translatio Global, una agencia de acompañamiento en subrogación gestacional en Colombia. Responde en ${language}. SOLO das información general. NUNCA das consejos médicos ni legales. Si la pregunta es sobre costos específicos, casos particulares, o temas médicos/legales, responde que un especialista debe contactarles. Responde en máximo 2 oraciones.` },
          { role: 'user', content: message },
        ],
        max_tokens: 200,
      });

      const answer = aiResponse.response || 'Lo siento, no puedo responder esa pregunta. ¿Te gustaría que un especialista te contacte?';

      try {
        await env.DB.prepare(
          'INSERT INTO chatbot_messages (session_id, role, content) VALUES (?, ?, ?)'
        ).bind(session_id, 'assistant', answer).run();
      } catch {}

      return new Response(JSON.stringify({
        type: 'answer',
        answer,
        escalate: true,
      }), {
        headers: { 'Content-Type': 'application/json' },
      });
    }
  } catch {}

  // Fallback: escalate to human
  return new Response(JSON.stringify({
    type: 'escalate',
    message: 'Para darte la mejor información, un especialista de nuestro equipo te contactará.',
    require_lead: true,
  }), {
    headers: { 'Content-Type': 'application/json' },
  });
};
