document.addEventListener('DOMContentLoaded', () => {
  const canvas = document.getElementById('particle-canvas');
  const ctx = canvas.getContext('2d');

  function resizeCanvas() {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
  }

  resizeCanvas(); // Initial resize
  window.addEventListener('resize', resizeCanvas);

  const particles = [];
  let particleCount;
  let maxDistance;

  function updateParticleSettings() {
      if (window.innerWidth < 768) {
          particleCount = 50; // Fewer particles for mobile/tablet
          maxDistance = 50; // Closer connection distance
      } else {
          particleCount = 100; // More particles for desktop
          maxDistance = 100; // Further connection distance
      }
  }

  updateParticleSettings(); // Set initial particle settings

  const speedFactor = 0.2;
  const mouse = { x: null, y: null, radius: 150 };

  function Particle(x, y) {
      this.x = x || Math.random() * canvas.width;
      this.y = y || Math.random() * canvas.height;
      this.vx = (Math.random() * 2 - 1) * speedFactor;
      this.vy = (Math.random() * 2 - 1) * speedFactor;
      this.radius = 2;
  }

  Particle.prototype.update = function () {
      this.x += this.vx;
      this.y += this.vy;

      if (this.x < 0 || this.x > canvas.width) this.vx *= -1;
      if (this.y < 0 || this.y > canvas.height) this.vy *= -1;
  };

  Particle.prototype.draw = function () {
    ctx.beginPath();
    ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
    ctx.fillStyle = '#fff'; // Set fill color to white
    ctx.fill();
    ctx.closePath();
};
    function connectParticles() {
        const lineColor = 'rgba(255, 255, 255, 0.5)'; // Set line color to semi-transparent white
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const distance = Math.hypot(particles[i].x - particles[j].x, particles[i].y - particles[j].y);
                if (distance < maxDistance) {
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.strokeStyle = lineColor; // Use the new line color
                    ctx.stroke();
                    ctx.closePath();
                }
            }
        }
    }

    function connectParticlesToMouse() {
        const lineColor = 'rgba(255, 255, 255, 0.5)'; // Set line color to semi-transparent white
        for (let i = 0; i < particles.length; i++) {
            const distance = Math.hypot(particles[i].x - mouse.x, particles[i].y - mouse.y);
            if (distance < maxDistance) {
                ctx.beginPath();
                ctx.moveTo(particles[i].x, particles[i].y);
                ctx.lineTo(mouse.x, mouse.y);
                ctx.strokeStyle = lineColor; // Use the new line color
                ctx.stroke();
                ctx.closePath();
            }
        }
    }

  function animateParticles() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      particles.forEach((particle) => {
          particle.update();
          particle.draw();
      });
      connectParticles();
      if (mouse.x !== null && mouse.y !== null) {
          connectParticlesToMouse();
      }
      requestAnimationFrame(animateParticles);
  }

  canvas.addEventListener('mousemove', (event) => {
      mouse.x = event.clientX;
      mouse.y = event.clientY;
  });

  canvas.addEventListener('mouseout', () => {
      mouse.x = null;
      mouse.y = null;
  });

  for (let i = 0; i < particleCount; i++) {
      particles.push(new Particle());
  }

  animateParticles();
});
