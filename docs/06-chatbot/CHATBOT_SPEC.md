# 🤖 Translatio Global — Chatbot Specification

**VERSIÓN:** 1.0
**Fecha:** 16 Abril 2026

---

## Propósito

Asistente virtual 24/7 que resuelve el primer contacto y FAQ, capturando leads mientras el equipo humano no está disponible.

---

## Capacidades

### 1. FAQ Automático
**Temas que cubre:**
- ¿Qué es la subrogación gestacional?
- ¿Es legal en Colombia?
- ¿Cuánto cuesta el proceso?
- ¿Cuánto tiempo toma?
- ¿Quiénes pueden acceder?
- ¿Qué requisitos hay?
- ¿Cómo se protege a la gestante?
- ¿Qué acompañamiento incluye Translatio?
- Diferencia entre subrogación gestacional y traditional

**Temas que NO cubre (deriva a humano):**
- Consejos médicos específicos
- Consejos legales específicos
- Cotización personalizada
- Estado de un caso en curso

### 2. Captura de Leads
**Campos obligatorios:**
- Nombre
- Email
- País de origen
- Idioma preferido

**Campos opcionales:**
- ¿Ya tienen información sobre subrogación? (Sí/No)
- ¿Prefieren contacto por email, WhatsApp o videollamada?

### 3. Derivación a Humano
**Triggers de derivación:**
- Usuario pregunta por costos específicos
- Usuario pregunta por su caso particular
- Usuario pregunta por aspectos médicos
- Usuario pregunta por aspectos legales
- Usuario pide hablar con alguien
- Bot no puede responder después de 2 intentos

**Mensaje de derivación:**
> "Gracias por su interés. Un miembro de nuestro equipo se pondrá en contacto con usted en las próximas 24 horas en su idioma. Si prefiere, puede agendar una consulta gratuita aquí: [link]"

### 4. Multi-idioma
- Detecta idioma del navegador
- Permite cambiar idioma manualmente
- Respuestas en el idioma del usuario
- 5 idiomas: ES, EN, PT, ZH, FR

---

## Base de Conocimiento

```json
{
  "faq": [
    {
      "id": "what_is",
      "question": {
        "es": "¿Qué es la subrogación gestacional?",
        "en": "What is gestational surrogacy?",
        "pt": "O que é substituição gestacional?",
        "zh": "什么是代孕？",
        "fr": "Qu'est-ce que la gestation pour autrui?"
      },
      "answer": {
        "es": "La subrogación gestacional es un proceso donde una mujer (gestante) lleva el embarazo de personas que no pueden concebir por sí mismas. En Colombia, este proceso está regulado y Translatio acompaña tanto a padres como a gestantes durante todo el camino.",
        "en": "Gestational surrogacy is a process where a woman (gestational carrier) carries a pregnancy for people who cannot conceive on their own. In Colombia, this process is regulated and Translatio supports both parents and carriers throughout the entire journey.",
        "pt": "A substituição gestacional é um processo em que uma mulher (gestante) leva a gravidez para pessoas que não podem conceber por si mesmas. Na Colômbia, este processo é regulamentado e a Translatio acompanha tanto os pais quanto as gestantes durante todo o caminho.",
        "zh": "代孕是一种由女性（代孕母亲）为无法自行受孕的人怀孕的过程。在哥伦比亚，这一过程受到监管，Translatio全程陪伴父母和代孕母亲。",
        "fr": "La gestation pour autrui est un processus où une femme (gestatrice) porte une grossesse pour des personnes qui ne peuvent pas concevoir par elles-mêmes. En Colombie, ce processus est réglementé et Translatio accompagne les parents et les gestatrices tout au long du parcours."
      }
    }
  ]
}
```

---

## Restricciones

```
EL CHATBOT NO:
  - Da consejos médicos
  - Da consejos legales
  - Cotiza precios específicos
  - Garantiza resultados
  - Comparte data de otros usuarios
  - Recomienda clínicas o abogados específicos

EL CHATBOT SÍ:
  - Informa sobre el proceso general
  - Captura datos de contacto
  - Responde FAQ
  - Deriva a humano cuando es necesario
  - Funciona 24/7 en 5 idiomas
```

---

## Technical Notes

- **Opción recomendada:** Widget embebido (Tidio, Landbot, o custom con OpenAI)
- **Integración:** WordPress plugin o script embebido
- **Analytics:** Tracking de conversaciones, leads capturados, idioma más usado
- **Privacidad:** GDPR notice antes de capturar datos personales
