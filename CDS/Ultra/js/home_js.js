document.addEventListener("DOMContentLoaded", () => {
    gsap.from(".animated-text", {
        y: 50,
        opacity: 0,
        duration: 2,
        ease: "power3.out",
    });
});

    document.addEventListener("DOMContentLoaded", () => {
        gsap.registerPlugin(ScrollTrigger);

        gsap.from(".about-title", {
            y: 50,
            opacity: 0,
            duration: 1.5,
            ease: "power3.out",
            scrollTrigger: {
                trigger: ".about-section",
                start: "top 80%", 
            },
        });

        gsap.from(".about-description", {
            y: 50,
            opacity: 0,
            duration: 1.5,
            delay: 0.2,
            ease: "power3.out",
            scrollTrigger: {
                trigger: ".about-section",
                start: "top 80%",
            },
        });

        gsap.from(".read-more", {
            y: 50,
            opacity: 0,
            duration: 1.5,
            delay: 0.4,
            ease: "power3.out",
            scrollTrigger: {
                trigger: ".about-section",
                start: "top 80%",
            },
        });
    });
    document.addEventListener("DOMContentLoaded", () => {
        gsap.registerPlugin(ScrollTrigger);

        gsap.from(".products-title", {
            y: 50,
            opacity: 0,
            duration: 1.5,
            ease: "power3.out",
            scrollTrigger: {
                trigger: ".products-section",
                start: "top 80%",
            },
        });

        gsap.from(".product-image", {
            opacity: 0,
            scale: 0.8,
            duration: 1.5,
            ease: "power3.out",
            stagger: 0.2, 
            scrollTrigger: {
                trigger: ".products-section",
                start: "top 80%",
            },
        });
    });
    document.addEventListener("DOMContentLoaded", () => {
        const galleryImages = document.querySelectorAll("#Gallery img");
      
        const observer = new IntersectionObserver(
          (entries, observer) => {
            entries.forEach((entry) => {
              if (entry.isIntersecting) {
                entry.target.classList.add("animate");
                observer.unobserve(entry.target);
              }
            });
          },
          {
            threshold: 0.2, 
          }
        );
      
        galleryImages.forEach((image) => {
          observer.observe(image);
        });
      });
      document.addEventListener("DOMContentLoaded", () => {
        const footerElements = document.querySelectorAll("#dynamicFooter .wow");
      
        const observer = new IntersectionObserver(
          (entries, observer) => {
            entries.forEach((entry) => {
              if (entry.isIntersecting) {
                entry.target.classList.add("animate");
              } else {
                entry.target.classList.remove("animate");
              }
            });
          },
          {
            threshold: 0.3, 
          }
        );
      
        footerElements.forEach((el) => observer.observe(el));
      });
      

   
            
    