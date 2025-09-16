/*
 * Copyright (c) 2025.
 */

document.addEventListener("DOMContentLoaded", () => {
    const tables = document.querySelectorAll(".dynamic-table-wrapper");

    tables.forEach(initTable);

    function initTable(tableWrapper) {
        const tableId = tableWrapper.id.replace("-wrapper", "");
        const endpoint = tableWrapper.dataset.endpoint;

        const table = tableWrapper.querySelector(`#${tableId}`);
        const tableBody = table.querySelector("tbody");
        const loadingSpinner = tableWrapper.querySelector(`#${tableId}-spinner`);
        const paginationDiv = tableWrapper.querySelector("#pagination"); // Assuming one pagination per component
        const searchInput = tableWrapper.querySelector("#search"); // Assuming one search per component

        let currentPage = 1;
        let perPage = 10;
        let currentSort = "";
        let currentOrder = "desc";
        let currentSearch = "";

        async function loadData() {
            loadingSpinner.style.display = "flex";
            table.style.display = "none";
            if (paginationDiv) paginationDiv.innerHTML = "";

            const params = new URLSearchParams({
                page: currentPage,
                per_page: perPage,
                sort: currentSort,
                order: currentOrder,
                search: currentSearch,
                html: "true", // Tell the backend we want HTML
            });

            try {
                const res = await fetch(`${endpoint}?${params}`);
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                const responseData = await res.json(); // Expect {html: "...", total: X, ...}

                // Render table rows
                tableBody.innerHTML = responseData.html;

                // Render pagination
                if (paginationDiv) {
                    const totalPages = Math.ceil(responseData.total / responseData.per_page);
                    for (let p = 1; p <= totalPages; p++) {
                        const btn = document.createElement("button");
                        btn.textContent = p;
                        // Add classes for styling, consider creating a component or utility for this
                        btn.className =
                            "inline-flex items-center gap-x-2 rounded-lg border border-gray-800 bg-gray-900 px-3 py-2 text-sm font-medium text-white shadow-2xs hover:bg-neutral-700 focus:bg-neutral-700 focus:outline-hidden disabled:pointer-events-none disabled:opacity-50";
                        if (p === responseData.page) btn.disabled = true;
                        btn.addEventListener("click", () => {
                            currentPage = p;
                            loadData();
                        });
                        paginationDiv.appendChild(btn);
                    }
                }

                // Update sort indicators
                tableWrapper.querySelectorAll("th[data-sort]").forEach((th) => {
                    th.classList.remove("asc", "desc");
                    if (th.dataset.sort === currentSort) {
                        th.classList.add(currentOrder);
                    }
                });
            } catch (error) {
                console.error(`Error loading data for ${tableId}:`, error);
                tableBody.innerHTML = `<tr><td colspan="100%" class="text-center text-red-500">Error loading data.</td></tr>`;
            } finally {
                loadingSpinner.style.display = "none";
                table.style.display = ""; // Revert to default display (table)
            }
        }

        if (searchInput) {
            searchInput.addEventListener("input", (e) => {
                currentSearch = e.target.value;
                currentPage = 1;
                loadData();
            });
        }

        tableWrapper.querySelectorAll("th[data-sort]").forEach((th) => {
            th.addEventListener("click", () => {
                const sortField = th.dataset.sort;
                if (currentSort === sortField) {
                    currentOrder = currentOrder === "asc" ? "desc" : "asc";
                } else {
                    currentSort = sortField;
                    currentOrder = "asc";
                }
                loadData();
            });
        });

        // Initial load
        const firstHeader = tableWrapper.querySelector("th[data-sort]");
        if (firstHeader) {
            currentSort = firstHeader.dataset.sort;
        }
        loadData();
    }
});
