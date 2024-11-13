const sidebarToggle = document.querySelector(".sidebar .toggle");
const sidebarItems = document.querySelectorAll(".sidebar  p");
sidebarToggle.addEventListener("click", () => {
  sidebarItems.forEach((p) => {
    if (p.style.display === "none" || p.style.display === "") {
      p.style.display = "block";
    } else {
      p.style.display = "none";
    }
  });
});
