// Highlight low stock items
function highlightLowStock() {
    const rows = document.querySelectorAll("table tr");
    rows.forEach(row => {
        const quantityCell = row.querySelector("td:nth-child(3)");
        if (quantityCell) {
            const quantity = parseInt(quantityCell.textContent, 10);
            if (quantity < 10) {
                row.classList.add("low-stock");
            }
        }
    });
}

// Live search functionality with suggestions
function setupSearchFeature() {
    const searchInput = document.getElementById("searchBar");
    const rows = document.querySelectorAll("table tbody tr");

    searchInput.addEventListener("input", () => {
        const query = searchInput.value.toLowerCase();
        rows.forEach(row => {
            const productName = row.querySelector("td:nth-child(2)").textContent.toLowerCase();
            if (productName.startsWith(query)) {
                row.style.display = ""; // Show rows that match the query
            } else {
                row.style.display = "none"; // Hide rows that don't match
            }
        });
    });
}

// Confirmation for delete action
function setupDeleteConfirmation() {
    const deleteButtons = document.querySelectorAll(".delete-btn");
    deleteButtons.forEach(button => {
        button.addEventListener("click", event => {
            if (!confirm("Are you sure you want to delete this product?")) {
                event.preventDefault();
            }
        });
    });
}

// Apply hover effect on rows
function applyRowHoverEffect() {
    const rows = document.querySelectorAll("table tr");
    rows.forEach(row => {
        row.addEventListener("mouseover", () => {
            row.classList.add("row-hover");
        });
        row.addEventListener("mouseout", () => {
            row.classList.remove("row-hover");
        });
    });
}

// Manual search with a button click
function manualSearch() {
    const query = document.getElementById("searchBar").value.toLowerCase();
    const rows = document.querySelectorAll("table tbody tr");

    rows.forEach(row => {
        const productName = row.querySelector("td:nth-child(2)").textContent.toLowerCase();
        if (productName.includes(query)) {
            row.style.display = ""; // Show rows that match the query
        } else {
            row.style.display = "none"; // Hide rows that don't match
        }
    });
}

// Initialize all functions on page load
document.addEventListener("DOMContentLoaded", () => {
    highlightLowStock();
    setupSearchFeature();
    setupDeleteConfirmation();
    applyRowHoverEffect();
});
