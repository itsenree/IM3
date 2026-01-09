// Function to handle tab switching
function switchTab(tab) {
    // Remove active class from all tabs
    document.querySelectorAll('.category_selection a').forEach(function(t) {
        t.classList.remove('detail_active');
        t.classList.add('detail_name');
    });

    // Add active class to the selected tab
    tab.classList.add('detail_active');
    tab.classList.remove('detail_name');

    // Hide all tab contents (any element that carries data-tab-content)
    document.querySelectorAll('#tab-content [data-tab-content]').forEach(function(el) {
        el.style.display = 'none';
    });

    // Show the selected tab content
    const selected = tab.getAttribute('data-tab');
    const selectedContent = document.querySelector('#tab-content [data-tab-content="' + selected + '"]');
    if (selectedContent) {
        selectedContent.style.display = '';
    }
}

// Automatically show the correct tab content on page load
function showActiveTabOnLoad() {
    // Check if a tab is specified in the URL query parameter
    const urlParams = new URLSearchParams(window.location.search);
    const activeTabParam = urlParams.get('tab');

    // Find the corresponding tab link
    let activeTab = null;
    if (activeTabParam) activeTab = document.querySelector('.category_selection a[data-tab="' + activeTabParam + '"]');


    // Default to the first tab if no valid tab is found
    if (!activeTab) activeTab = document.querySelector('.category_selection a.detail_active');

    // Switch to the active tab
    if (activeTab) switchTab(activeTab);
    
}

// Add click event listeners to all tabs
document.querySelectorAll('.category_selection a').forEach(function(tab) {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        switchTab(tab);

        // Update the URL query parameter without reloading the page
        const selectedTab = tab.getAttribute('data-tab');
        const newUrl = window.location.pathname + '?tab=' + selectedTab;
        history.replaceState(null, '', newUrl);
    });
});

// Show the correct tab content on page load
showActiveTabOnLoad();
