#!/usr/bin/env python3
"""
MOLINO Project Lead - Translatio Global Fix Script v2
Uses string find/replace instead of regex for complex replacements.
"""
import os
import json

DIST = '/root/.openclaw/workspace/translatio-global/dist'

def find_and_replace(html, old, new):
    """Simple string replacement."""
    return html.replace(old, new)

def find_script_block(html):
    """Find the chatbot script block - starts with <script>(function(){ and ends with })();</script>"""
    start_marker = '<script>(function(){const lang ='
    end_marker = '})();</script>'
    
    start_idx = html.find(start_marker)
    if start_idx == -1:
        print(f'  WARNING: Could not find chatbot script start')
        return html, False
    
    # Find the end marker after the start
    end_idx = html.find(end_marker, start_idx)
    if end_idx == -1:
        print(f'  WARNING: Could not find chatbot script end')
        return html, False
    
    end_idx += len(end_marker)
    old_script = html[start_idx:end_idx]
    return old_script, True

def build_chatbot_script(lang, faq_answers, disclaimer, escalate_text, thanks_text, placeholder):
    """Build the complete new chatbot script."""
    faq_entries = []
    for key, val in faq_answers.items():
        escaped = val.replace("\\", "\\\\").replace("'", "\\'")
        faq_entries.append(f"    '{key}': '{escaped}'")
    faq_obj = "{\n" + ",\n".join(faq_entries) + "\n  }"
    
    return f'''<script>(function(){{
  const lang = "{lang}";
  const faqAnswers = {faq_obj};

  const toggle = document.getElementById('chatbot-toggle');
  const window_ = document.getElementById('chatbot-window');
  const close = document.getElementById('chatbot-close');
  const input = document.getElementById('chatbot-input');
  const sendBtn = document.getElementById('chatbot-send');
  const messages = document.getElementById('chatbot-messages');
  const quickReplies = document.getElementById('quick-replies');
  const leadForm = document.getElementById('lead-form');
  const chatInput = document.getElementById('chat-input');

  // CRIT-003 FIX: Safe DOM construction (no innerHTML with user text)
  function addMessage(text, isUser) {{
    var div = document.createElement('div');
    div.className = isUser ? 'flex gap-2 justify-end' : 'flex gap-2';
    var p = document.createElement('p');
    p.className = 'text-sm';
    p.textContent = text; // XSS-safe
    if (isUser) {{
      var bubble = document.createElement('div');
      bubble.className = 'bg-[#2C5F8A] text-white rounded-2xl rounded-tr-sm px-4 py-2 max-w-[80%]';
      bubble.appendChild(p);
      div.appendChild(bubble);
    }} else {{
      var avatar = document.createElement('div');
      avatar.className = 'w-8 h-8 rounded-full bg-[#2C5F8A] text-white flex items-center justify-center text-sm shrink-0';
      avatar.textContent = 'T';
      var bubble = document.createElement('div');
      bubble.className = 'bg-gray-100 rounded-2xl rounded-tl-sm px-4 py-2 max-w-[80%]';
      bubble.appendChild(p);
      div.appendChild(avatar);
      div.appendChild(bubble);
    }}
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
  }}

  function showLeadForm() {{
    leadForm.classList.remove('hidden');
    chatInput.classList.add('hidden');
  }}

  // CRIT-002 FIX: Client-side FAQ matching
  function matchFAQ(message) {{
    var msg = message.toLowerCase().trim();
    var keywords = {{
      'what_is': ['subrog','surrogac','substitui','代孕','gestation pour autrui','gpa','qué es','what is','o que é','什么是'],
      'legal': ['legal','colombia','colombie','合法','yes','sí','sim','oui'],
      'info': ['información','information','informações','信息','consulta','consultation','contacto','contact','contato','联系','quiero','want','quero','je veux','help','ayuda','ajuda','aide']
    }};
    var bestMatch = null, bestScore = 0;
    for (var key in keywords) {{
      var score = 0;
      for (var i = 0; i < keywords[key].length; i++) {{
        if (msg.indexOf(keywords[key][i]) !== -1) score++;
      }}
      if (score > bestScore) {{ bestScore = score; bestMatch = key; }}
    }}
    return bestScore > 0 ? bestMatch : null;
  }}

  function sendChat(message) {{
    addMessage(message, true);
    quickReplies && quickReplies.classList.add('hidden');
    var matched = matchFAQ(message);
    if (matched && faqAnswers[matched]) {{
      addMessage(faqAnswers[matched]);
      setTimeout(function() {{
        addMessage("{escalate_text}");
        showLeadForm();
      }}, 1500);
    }} else {{
      addMessage("{escalate_text}");
      showLeadForm();
    }}
  }}

  toggle && toggle.addEventListener('click', function() {{
    var open = !window_.classList.contains('hidden');
    window_.classList.toggle('hidden');
    toggle.setAttribute('aria-expanded', !open);
    var pulse = document.getElementById('chatbot-pulse');
    pulse && pulse.classList.add('hidden');
  }});

  close && close.addEventListener('click', function() {{
    window_.classList.add('hidden');
    toggle.setAttribute('aria-expanded', 'false');
  }});

  sendBtn && sendBtn.addEventListener('click', function() {{
    var msg = input.value.trim();
    if (msg) {{ sendChat(msg); input.value = ''; }}
  }});

  input && input.addEventListener('keypress', function(e) {{
    if (e.key === 'Enter') {{
      var msg = input.value.trim();
      if (msg) {{ sendChat(msg); input.value = ''; }}
    }}
  }});

  document.querySelectorAll('.quick-reply').forEach(function(btn) {{
    btn.addEventListener('click', function() {{
      sendChat(btn.textContent);
    }});
  }});

  // CRIT-004 FIX: Lead submit with GDPR checkbox validation + email validation
  var leadSubmitBtn = document.getElementById('lead-submit');
  leadSubmitBtn && leadSubmitBtn.addEventListener('click', function() {{
    var name = document.getElementById('lead-name').value.trim();
    var email = document.getElementById('lead-email').value.trim();
    var gdprBox = document.getElementById('lead-gdpr');
    var gdprConsent = gdprBox ? gdprBox.checked : false;
    var emailRegex = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;

    if (!name || !email) return;
    if (!emailRegex.test(email)) {{
      addMessage("\\u26a0\\ufe0f Please enter a valid email address.");
      return;
    }}
    if (!gdprConsent) {{
      addMessage("\\u26a0\\ufe0f You must accept the privacy policy to continue.");
      return;
    }}

    try {{
      var leads = JSON.parse(localStorage.getItem('translatio_leads') || '[]');
      leads.push({{ name: name, email: email, language: lang, source: 'chatbot', gdpr_consent: true, timestamp: new Date().toISOString() }});
      localStorage.setItem('translatio_leads', JSON.stringify(leads));
    }} catch(e) {{}}

    leadForm.classList.add('hidden');
    chatInput.classList.remove('hidden');
    addMessage("{thanks_text}");
  }});
}})();</script>'''


def fix_file(lang, config):
    filepath = config['file']
    with open(filepath, 'r', encoding='utf-8') as f:
        html = f.read()

    is_zh = lang == 'zh'
    changed = []

    # =========================================================================
    # FIX: CRIT-001 - Nav links → anchor links (desktop + mobile)
    # =========================================================================
    for old_href, new_href in config['nav_links'].items():
        count = html.count(f'href="{old_href}"')
        if count > 0:
            html = html.replace(f'href="{old_href}"', f'href="{new_href}"')
            changed.append(f'CRIT-001: Fixed {count} nav links "{old_href}" → "{new_href}"')

    # =========================================================================
    # FIX: MAY-002 - Translate meta description
    # =========================================================================
    old_meta = '<meta name="description" content="Translatio Global - Acompañamiento integral en subrogación gestacional en Colombia"'
    new_meta = f'<meta name="description" content="{config["meta_desc"]}"'
    if old_meta in html:
        html = html.replace(old_meta, new_meta)
        changed.append('MAY-002: Translated meta description')

    # =========================================================================
    # FIX: MIN-002 - Remove generator tag
    # =========================================================================
    gen_tag_start = '<meta name="generator"'
    if gen_tag_start in html:
        idx = html.find(gen_tag_start)
        end = html.find('>', idx) + 1
        html = html[:idx] + html[end:]
        changed.append('MIN-002: Removed generator tag')

    # =========================================================================
    # FIX: MAY-005 - Conditional font loading
    # =========================================================================
    if not is_zh:
        old_fonts = '&family=Noto+Sans+SC:wght@400;500;600;700'
        if old_fonts in html:
            html = html.replace(old_fonts, '')
            changed.append('MAY-005: Removed Noto Sans SC (non-ZH page)')

    # =========================================================================
    # FIX: MAY-001 - Language selector click toggle
    # =========================================================================
    html = html.replace('class="relative group"', 'class="relative" id="lang-selector"')
    html = html.replace('hidden group-hover:block min-w-[140px]', 'hidden min-w-[140px] lang-dropdown')
    changed.append('MAY-001: Language selector now click-based')

    # Add toggle script after mobile menu script
    mobile_script = '<script type="module">document.getElementById("mobile-menu-btn")?.addEventListener("click",()=>{document.getElementById("mobile-menu")?.classList.toggle("hidden")});</script>'
    lang_toggle = '''<script type="module">
(function(){
  var ls=document.getElementById('lang-selector');
  var dd=ls?ls.querySelector('.lang-dropdown'):null;
  var btn=ls?ls.querySelector('button'):null;
  if(!btn||!dd)return;
  btn.addEventListener('click',function(e){e.stopPropagation();dd.classList.toggle('hidden');});
  document.addEventListener('click',function(){dd.classList.add('hidden');});
  document.addEventListener('touchstart',function(e){if(ls&&!ls.contains(e.target))dd.classList.add('hidden');},{passive:true});
})();
</script>'''
    html = html.replace(mobile_script, mobile_script + lang_toggle)
    changed.append('MAY-001: Added click toggle JS')

    # =========================================================================
    # FIX: MIN-003 - Translate chatbot disclaimer
    # =========================================================================
    # Find and replace the disclaimer paragraph
    old_disclaimer_en = 'This information is general and does not constitute medical or legal advice.'
    if lang != 'en' and lang != 'es':
        if old_disclaimer_en in html:
            html = html.replace(old_disclaimer_en, config['disclaimer'])
            changed.append(f'MIN-003: Translated disclaimer to {lang}')

    # =========================================================================
    # FIX: CRIT-004 - Add GDPR checkbox to lead form HTML
    # =========================================================================
    gdpr_checkbox_html = f'''<div class="flex items-start gap-2 mb-2">
      <input type="checkbox" id="lead-gdpr" class="mt-1">
      <label for="lead-gdpr" class="text-xs text-gray-600">{config["gdpr_label"]}</label>
    </div>'''
    
    submit_btn_marker = '<button id="lead-submit"'
    if gdpr_checkbox_html not in html:
        html = html.replace(submit_btn_marker, gdpr_checkbox_html + '\n    ' + submit_btn_marker)
        changed.append('CRIT-004: Added GDPR checkbox to lead form')

    # =========================================================================
    # FIX: CRIT-002 + CRIT-003 + CRIT-004 + MIN-005 - Replace chatbot script
    # =========================================================================
    script_start = '<script>(function(){const lang ='
    script_end = '})();</script>'
    
    start_idx = html.find(script_start)
    end_idx = html.find(script_end, start_idx)
    
    if start_idx != -1 and end_idx != -1:
        end_idx += len(script_end)
        old_block = html[start_idx:end_idx]
        
        # Escape quotes for JS strings
        esc = config['escalate_text'].replace("'", "\\'")
        thx = config['thanks_text'].replace("'", "\\'")
        
        new_script = build_chatbot_script(
            lang, config['faq_answers'], config['disclaimer'],
            esc, thx, config['placeholder']
        )
        
        html = html[:start_idx] + new_script + html[end_idx:]
        changed.append('CRIT-002/003/004/MIN-005: Replaced chatbot script (FAQ, XSS fix, GDPR, email validation)')

    # =========================================================================
    # FIX: Contact form - Store locally (no API)
    # =========================================================================
    contact_script_start = '<script type="module">const e=document.getElementById("contact-form");'
    contact_script_end = '</script>'
    
    cs_start = html.find(contact_script_start)
    if cs_start != -1:
        # Find the closing </script> after this
        cs_end = html.find(contact_script_end, cs_start) + len(contact_script_end)
        
        new_contact = '''<script type="module">var e=document.getElementById("contact-form");e&&e.addEventListener("submit",function(a){a.preventDefault();if(e.querySelector('[name="website"]')?.value)return;var t=e.querySelector(".btn-text"),n=e.querySelector(".btn-loading");var gdprCb=e.querySelector('[name="gdpr"]');if(!gdprCb||!gdprCb.checked){alert("Please accept the privacy policy");return;}t&&t.classList.add("hidden");n&&n.classList.remove("hidden");var s={name:e.querySelector('[name="name"]').value.trim(),email:e.querySelector('[name="email"]').value.trim(),country:e.querySelector('[name="country"]').value.trim(),message:e.querySelector('[name="message"]').value.trim(),gdpr_consent:gdprCb.checked,language:e.dataset.lang||"es"};try{var subs=JSON.parse(localStorage.getItem("translatio_contacts")||"[]");subs.push(Object.assign({},s,{timestamp:new Date().toISOString()}));localStorage.setItem("translatio_contacts",JSON.stringify(subs));e.style.display="none";document.getElementById("form-success")?.classList.remove("hidden")}catch(err){t&&t.classList.remove("hidden");n&&n.classList.add("hidden")}});</script>'''
        
        html = html[:cs_start] + new_contact + html[cs_end:]
        changed.append('Contact form: Stores locally (no API dependency)')

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(html)
    
    print(f'\n✅ Fixed: {filepath}')
    for c in changed:
        print(f'  - {c}')
    return changed


# Language configurations
configs = {
    'es': {
        'file': f'{DIST}/index.html',
        'meta_desc': 'Translatio Global - Acompañamiento integral en subrogación gestacional en Colombia',
        'disclaimer': 'Esta información es general y no constituye asesoría médica ni legal.',
        'gdpr_label': 'Acepto la <a href="#privacy" class="underline">política de privacidad</a> *',
        'escalate_text': 'Para darte la mejor información, me gustaría que un especialista te contacte. ¿Me compartes tu nombre y email?',
        'thanks_text': '¡Gracias! Un especialista te contactará en 24 horas. 🤝',
        'placeholder': 'Escribe tu pregunta...',
        'nav_links': {
            '/servicios': '#services',
            '/proceso': '#process',
            '/quienes-somos': '#',
            '/contacto': '#contact',
        },
        'faq_answers': {
            'what_is': 'La subrogación gestacional es un proceso donde una mujer (gestante) lleva el embarazo para personas o familias que no pueden concebir por sí mismas. En Colombia, está regulado por ley y Translatio te acompaña en todo el proceso con apoyo legal, médico y emocional.',
            'legal': 'Sí, la subrogación gestacional es legal en Colombia. El país tiene un marco regulatorio claro que protege a todas las partes involucradas. Translatio trabaja con abogados especializados para garantizar un proceso seguro y transparente.',
            'info': '¡Con gusto! Te recomendamos solicitar una consulta gratuita con nuestro equipo. Podemos resolver todas tus dudas y evaluar tu caso de forma personalizada. ¿Te gustaría que un especialista te contacte?',
        },
    },
    'en': {
        'file': f'{DIST}/en/index.html',
        'meta_desc': 'Translatio Global - Professional gestational surrogacy support in Colombia for intended parents worldwide',
        'disclaimer': 'This information is general and does not constitute medical or legal advice.',
        'gdpr_label': 'I accept the <a href="#privacy" class="underline">privacy policy</a> *',
        'escalate_text': "To give you the best information, I'd like a specialist to contact you. Can you share your name and email?",
        'thanks_text': 'Thank you! A specialist will contact you within 24 hours. 🤝',
        'placeholder': 'Type your question...',
        'nav_links': {
            '/en/services': '#services',
            '/en/process': '#process',
            '/en/about': '#',
            '/en/contact': '#contact',
        },
        'faq_answers': {
            'what_is': 'Gestational surrogacy is a process where a woman (carrier) carries a pregnancy for individuals or families who cannot conceive on their own. In Colombia, it is regulated by law and Translatio accompanies you throughout with legal, medical and emotional support.',
            'legal': 'Yes, gestational surrogacy is legal in Colombia. The country has a clear regulatory framework that protects all parties involved. Translatio works with specialized lawyers to ensure a safe and transparent process.',
            'info': 'With pleasure! We recommend requesting a free consultation with our team. We can answer all your questions and evaluate your case personally. Would you like a specialist to contact you?',
        },
    },
    'pt': {
        'file': f'{DIST}/pt/index.html',
        'meta_desc': 'Translatio Global - Acompanhamento profissional em substituição gestacional na Colômbia para pais intencionais',
        'disclaimer': 'Esta informação é geral e não constitui aconselhamento médico ou jurídico.',
        'gdpr_label': 'Aceito a <a href="#privacy" class="underline">política de privacidade</a> *',
        'escalate_text': 'Para dar a melhor informação, gostaria que um especialista entrasse em contato. Pode compartilhar seu nome e email?',
        'thanks_text': 'Obrigado! Um especialista entrará em contato em 24 horas. 🤝',
        'placeholder': 'Digite sua pergunta...',
        'nav_links': {
            '/pt/servicos': '#services',
            '/pt/processo': '#process',
            '/pt/quem-somos': '#',
            '/pt/contato': '#contact',
        },
        'faq_answers': {
            'what_is': 'A substituição gestacional é um processo onde uma mulher (gestante) leva a gestação para pessoas ou famílias que não podem conceber por si mesmas. Na Colômbia, é regulamentado por lei e a Translatio acompanha todo o processo com apoio legal, médico e emocional.',
            'legal': 'Sim, a substituição gestacional é legal na Colômbia. O país tem um marco regulatório claro que protege todas as partes envolvidas. A Translatio trabalha com advogados especializados para garantir um processo seguro e transparente.',
            'info': 'Com prazer! Recomendamos solicitar uma consulta gratuita com nossa equipe. Podemos tirar todas as suas dúvidas e avaliar seu caso de forma personalizada. Gostaria que um especialista entrasse em contato?',
        },
    },
    'zh': {
        'file': f'{DIST}/zh/index.html',
        'meta_desc': 'Translatio Global - 哥伦比亚专业代孕服务，为全球准父母提供全方位支持',
        'disclaimer': '此信息仅供参考，不构成医疗或法律建议。',
        'gdpr_label': '我接受<a href="#privacy" class="underline">隐私政策</a> *',
        'escalate_text': '为了给您最好的服务，请留下您的姓名和邮箱，我们的专家会联系您。',
        'thanks_text': '谢谢！专家将在24小时内与您联系。🤝',
        'placeholder': '请输入您的问题...',
        'nav_links': {
            '/zh/services': '#services',
            '/zh/process': '#process',
            '/zh/about': '#',
            '/zh/contact': '#contact',
        },
        'faq_answers': {
            'what_is': '代孕是一个过程，一位女性（代孕妈妈）为无法自行怀孕的个人或家庭怀孕。在哥伦比亚，代孕受法律监管，Translatio全程提供法律、医疗和情感支持。',
            'legal': '是的，代孕在哥伦比亚是合法的。该国拥有明确的监管框架，保护所有相关方。Translatio与专业律师合作，确保流程安全透明。',
            'info': '很高兴为您提供信息！我们建议您申请免费咨询。我们可以解答您的所有疑问，并个性化评估您的情况。您希望我们的专家联系您吗？',
        },
    },
    'fr': {
        'file': f'{DIST}/fr/index.html',
        'meta_desc': 'Translatio Global - Accompagnement professionnel en gestation pour autrui en Colombie pour les parents intentionnels',
        'disclaimer': 'Ces informations sont générales et ne constituent pas un avis médical ou juridique.',
        'gdpr_label': "J'accepte la <a href=\"#privacy\" class=\"underline\">politique de confidentialité</a> *",
        'escalate_text': "Pour vous donner la meilleure information, j'aimerais qu'un spécialiste vous contacte. Pouvez-vous partager votre nom et email ?",
        'thanks_text': 'Merci ! Un spécialiste vous contactera dans 24 heures. 🤝',
        'placeholder': 'Tapez votre question...',
        'nav_links': {
            '/fr/services': '#services',
            '/fr/processus': '#process',
            '/fr/a-propos': '#',
            '/fr/contact': '#contact',
        },
        'faq_answers': {
            'what_is': "La gestation pour autrui (GPA) est un processus où une femme (gestante) porte une grossesse pour des personnes ou familles qui ne peuvent pas concevoir par elles-mêmes. En Colombie, c'est réglementé par la loi et Translatio vous accompagne avec un soutien juridique, médical et émotionnel.",
            'legal': "Oui, la GPA est légale en Colombie. Le pays dispose d'un cadre réglementaire clair qui protège toutes les parties. Translatio travaille avec des avocats spécialisés pour garantir un processus sûr et transparent.",
            'info': "Avec plaisir ! Nous vous recommandons de demander une consultation gratuite. Nous pouvons répondre à toutes vos questions et évaluer votre cas. Souhaitez-vous qu'un spécialiste vous contacte ?",
        },
    },
}

all_changes = {}
for lang, config in configs.items():
    all_changes[lang] = fix_file(lang, config)

print('\n' + '='*60)
print('🎉 ALL 5 HTML FILES FIXED!')
print('='*60)
for lang, changes in all_changes.items():
    print(f'\n{lang.upper()}: {len(changes)} fixes applied')
