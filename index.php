<?php
require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sonrisa Perfecta | Clínica dental juvenil</title>
    <meta
      name="description"
      content="Clínica dental moderna y cercana para jóvenes universitarios que buscan confianza, estética y resultados visibles."
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
    <div class="page-shell">
      <header class="topbar">
        <a href="#" class="brand">
          <span class="brand-mark">✦</span>
          Sonrisa Perfecta
        </a>
        <nav class="nav-links">
          <a href="#beneficios">Beneficios</a>
          <a href="#servicios">Servicios</a>
          <a href="#testimonios">Testimonios</a>
          <a href="#contacto" class="nav-cta">Reservar cita</a>
          <?php if (isLoggedIn()): ?>
            <span class="user-pill">Hola, <?= htmlspecialchars(currentUserName()) ?></span>
            <?php if (currentUserRole() === 'admin'): ?>
              <a href="dashboard.php" class="nav-cta">Dashboard</a>
            <?php endif; ?>
            <a href="logout.php" class="nav-cta">Cerrar sesión</a>
          <?php else: ?>
            <a href="login.php" class="nav-cta">Iniciar sesión</a>
          <?php endif; ?>
        </nav>
      </header>

      <main>
        <section class="hero">
          <div class="hero-copy">
            <p class="eyebrow">Sonrisa fresca, confianza total</p>
            <h1>Tu mejor sonrisa empieza con una clínica que entiende a los jóvenes.</h1>
            <p class="hero-text">
              En Sonrisa Perfecta combinamos tecnología, estética y un trato cercano para que cada visita se sienta cómoda, rápida y transformadora.
            </p>
            <div class="hero-actions">
              <a href="#contacto" class="btn btn-primary">Reserva tu evaluación</a>
              <a href="#servicios" class="btn btn-secondary">Ver servicios</a>
            </div>
            <ul class="hero-points">
              <li>Resultados visibles en pocas sesiones</li>
              <li>Ambiente relajante y moderno</li>
              <li>Atención personalizada y cercana</li>
            </ul>
          </div>

          <div class="hero-visual">
            <div class="hero-card">
              <div class="hero-illustration" aria-hidden="true">
                <svg viewBox="0 0 500 500" role="img">
                  <rect x="60" y="70" width="380" height="360" rx="42" fill="#f4fbff" />
                  <circle cx="250" cy="170" r="110" fill="#ffffff" />
                  <path d="M150 205c18-65 85-95 146-77 37 11 52 35 60 64" fill="none" stroke="#87c8ff" stroke-width="16" stroke-linecap="round" />
                  <path d="M170 250c20 45 58 72 93 72 38 0 72-24 92-72" fill="none" stroke="#2f6bff" stroke-width="18" stroke-linecap="round" />
                  <path d="M205 290c17 16 30 24 45 24 17 0 32-10 49-31" fill="none" stroke="#ff8fb5" stroke-width="12" stroke-linecap="round" />
                  <circle cx="210" cy="165" r="8" fill="#1f3558" />
                  <circle cx="292" cy="165" r="8" fill="#1f3558" />
                  <path d="M215 225c12 12 20 18 35 18 14 0 24-8 35-18" fill="none" stroke="#1f3558" stroke-width="7" stroke-linecap="round" />
                </svg>
              </div>
            </div>
            <div class="floating-badge">
              <span class="badge-icon">✦</span>
              <div>
                <strong>4.9/5</strong>
                <p>Pacientes felices</p>
              </div>
            </div>
            <div class="floating-pill">Odontología estética • 100% cómoda</div>
          </div>
        </section>

        <section class="trust-strip" aria-label="Puntos de confianza">
          <span>✓ Ortodoncia invisible</span>
          <span>✓ Blanqueamiento seguro</span>
          <span>✓ Sonrisas naturales</span>
        </section>

        <section class="benefits" id="beneficios">
          <div class="section-heading">
            <p class="eyebrow eyebrow-dark">Por qué elegirnos</p>
            <h2>Diseñamos una experiencia que se siente tan bien como se ve.</h2>
          </div>
          <div class="benefit-grid">
            <article>
              <h3>Confianza desde el primer vistazo</h3>
              <p>Un ambiente claro, amable y premium que te hace sentir en buenas manos.</p>
            </article>
            <article>
              <h3>Resultados visibles</h3>
              <p>Tratamientos enfocados en mejorar tu sonrisa con resultados naturales y efectivos.</p>
            </article>
            <article>
              <h3>Comodidad total</h3>
              <p>Tu bienestar es prioridad con protocolos suaves, modernos y sin complicaciones.</p>
            </article>
          </div>
        </section>

        <section class="services" id="servicios">
          <div class="section-heading">
            <p class="eyebrow eyebrow-dark">Servicios destacados</p>
            <h2>Soluciones sencillas para una sonrisa brillante.</h2>
          </div>
          <div class="service-grid">
            <article>
              <h3>Blanqueamiento</h3>
              <p>Un tratamiento rápido y seguro para una sonrisa más luminosa.</p>
            </article>
            <article>
              <h3>Ortodoncia</h3>
              <p>Opciones modernas y discretas para alinear tu sonrisa con estilo.</p>
            </article>
            <article>
              <h3>Rehabilitación</h3>
              <p>Recupera tu confort y confianza con tratamientos de alta precisión.</p>
            </article>
          </div>
        </section>

        <section class="testimonials" id="testimonios">
          <div class="testimonial-card">
            <p>“Llegué con inseguridad y salí sintiéndome increíble. El proceso fue súper claro y cómodo.”</p>
            <strong>María, 22 años</strong>
          </div>
          <div class="testimonial-card">
            <p>“La clínica tiene una energía muy fresca. Me encantó que todo se sintiera tan cuidado y profesional.”</p>
            <strong>Tomás, 24 años</strong>
          </div>
        </section>

        <section class="cta-section" id="contacto">
          <h2>Tu sonrisa merece sentirse increíble.</h2>
          <p>Agenda hoy tu primera valoración y descubre cómo lucir y sentirte mejor.</p>
          <a href="#" class="btn btn-primary">Agendar cita</a>
        </section>
      </main>

      <footer class="footer">
        <p>Sonrisa Perfecta · Odontología joven, limpia y confiable.</p>
      </footer>
    </div>
  </body>
</html>
