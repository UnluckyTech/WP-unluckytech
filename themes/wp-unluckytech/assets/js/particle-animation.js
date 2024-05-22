document.addEventListener('DOMContentLoaded', function () {
    var canvas = document.getElementById('particle-canvas');
    var ctx = canvas.getContext('2d');

    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    var particles = [];
    var particleCount = 100;
    var maxDistance = 100;
    var speedFactor = 0.5; // Adjust this value to control the speed
    var mouse = { x: null, y: null, radius: 150 };

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
        ctx.fillStyle = '#fff';
        ctx.fill();
        ctx.closePath();
    };

    function initParticles() {
        for (var i = 0; i < particleCount; i++) {
            particles.push(new Particle());
        }
    }

    function connectParticles() {
        for (var i = 0; i < particles.length; i++) {
            for (var j = i + 1; j < particles.length; j++) {
                var distance = Math.hypot(particles[i].x - particles[j].x, particles[i].y - particles[j].y);
                if (distance < maxDistance) {
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.strokeStyle = 'rgba(255, 255, 255, 0.5)';
                    ctx.stroke();
                    ctx.closePath();
                }
            }
        }
    }

    function connectParticlesToMouse() {
        for (var i = 0; i < particles.length; i++) {
            var distance = Math.hypot(particles[i].x - mouse.x, particles[i].y - mouse.y);
            if (distance < maxDistance) {
                ctx.beginPath();
                ctx.moveTo(particles[i].x, particles[i].y);
                ctx.lineTo(mouse.x, mouse.y);
                ctx.strokeStyle = 'rgba(255, 255, 255, 0.5)';
                ctx.stroke();
                ctx.closePath();
            }
        }
    }

    function animateParticles() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particles.forEach(function (particle) {
            particle.update();
            particle.draw();
        });
        connectParticles();
        if (mouse.x !== null && mouse.y !== null) {
            connectParticlesToMouse();
        }
        requestAnimationFrame(animateParticles);
    }

    window.addEventListener('resize', function () {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    });

    canvas.addEventListener('mousemove', function (event) {
        mouse.x = event.clientX;
        mouse.y = event.clientY;
    });

    canvas.addEventListener('mouseout', function () {
        mouse.x = null;
        mouse.y = null;
    });

    initParticles();
    animateParticles();
});