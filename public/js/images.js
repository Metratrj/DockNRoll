/*
 * Copyright (c) 2025.
 */

document.addEventListener("DOMContentLoaded", () => {
  const tableBody = document.querySelector("#imageTable tbody");
  const paginationDiv = document.querySelector("#pagination");
  const searchInput = document.querySelector("#searchInput");

  let currentPage = 1;
  let perPage = 10;
  let currentSort = "RepoTags";
  let currentOrder = "desc";
  let currentSearch = "";

  async function loadImages() {
    const params = new URLSearchParams({
      page: currentPage,
      per_page: perPage,
      sort: currentSort,
      order: currentOrder,
      search: currentSearch,
    });

    const res = await fetch(`/images/search?${params}`);
    const data = await res.json();

    // render table
    tableBody.innerHTML = "";
    data.data.forEach((img) => {
      const row = document.createElement("tr");
      row.innerHTML = `
                    <td>${(img.RepoTags && img.RepoTags[0]) || "&lt;none&gt;"}</td>
        <td>${(img.Size / 1024 / 1024).toFixed(2)} MB</td>
            `;
      tableBody.append(row);
    });

    // Pagination rendern
    paginationDiv.innerHTML = "";
    const totalPages = Math.ceil(data.total / data.per_page);
    for (let p = 1; p <= totalPages; p++) {
      const btn = document.createElement("button");
      btn.textContent = p;
      if (p === data.page) btn.disabled = true;
      btn.addEventListener("click", () => {
        currentPage = p;
        loadImages();
      });
      paginationDiv.appendChild(btn);
    }
  }
  // Suche (on input)
  searchInput.addEventListener("input", (e) => {
    currentSearch = e.target.value;
    currentPage = 1;
    loadImages();
  });

  // Sortierung (Header click)
  document.querySelectorAll("#imageTable th[data-sort]").forEach((th) => {
    th.addEventListener("click", () => {
      const sortField = th.dataset.sort;
      if (currentSort === sortField) {
        currentOrder = currentOrder === "asc" ? "desc" : "asc";
      } else {
        currentSort = sortField;
        currentOrder = "asc";
      }
      loadImages();
    });
  });

  loadImages();
});
