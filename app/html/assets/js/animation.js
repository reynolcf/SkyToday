
const intersectionCallback = (entries) => {
  for (const entry of entries) {
    
    if (entry.isIntersecting) {
      
      entry.target.classList.add("animate");
    }
  }
};


const observer = new IntersectionObserver(intersectionCallback);


const items = document.querySelectorAll(".slide-in-left, .slide-in-up, .slide-in-right, .slide-in-down");
for (const item of items) {
  observer.observe(item);
}
