document.querySelectorAll('.category_selection a').forEach(function(tabLink) {
    tabLink.addEventListener('click', function(e) {
        e.preventDefault();
        const tab = tabLink.getAttribute('data-tab');
        const tabContent = document.getElementById('tab-content');
        tabContent.style.opacity = 0;
        setTimeout(function() {
            fetch(window.location.pathname + '?tab=' + tab)
                .then(response => response.text())
                .then(html => {
                    // Extract only the tab-content div from the response
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.getElementById('tab-content');
                    if (newContent) {
                        tabContent.innerHTML = newContent.innerHTML;
                        tabContent.style.opacity = 1;
                        // Update active tab styling
                        document.querySelectorAll('.category_selection a').forEach(a => {
                            a.className = 'detail_name';
                        });
                        tabLink.className = 'detail_active';
                        // Update URL without scrolling
                        history.replaceState(null, '', '?tab=' + tab);
                    }
                });
        }, 300);
    });
});