document.addEventListener('DOMContentLoaded', function () {
    initTabsSettings();
    initTabsWidget();
});

document.addEventListener('htmx:afterSwap', () => {
    initTabsSettings();
    initTabsWidget();
});

document.addEventListener('widgetSettingsLoaded', () => {
    initTabsSettings();
});

document.addEventListener('widgetInitialized', (e) => {
    if (e.detail.widgetName === 'tabs') {
        initTabsWidget();
    }
});

document.addEventListener('widgetRefreshed', (e) => {
    if (e.detail.widgetName === 'tabs') {
        initTabsWidget();
    }
});

function initTabsWidget() {
    const tabsWidgets = document.querySelectorAll('.tabs-widget');

    tabsWidgets.forEach(widget => {
        if (widget.hasAttribute('data-tabs-initialized')) return;
        widget.setAttribute('data-tabs-initialized', 'true');

        const leftContainer = widget.querySelector('.tabs-left-container');
        if (leftContainer) {
            initLeftTabs(leftContainer);
        }
    });
}

function initLeftTabs(container) {
    const buttons = container.querySelectorAll('.tab-left-button');
    const panels = container.querySelectorAll('.tab-left-panel');

    buttons.forEach((button, index) => {
        button.addEventListener('click', () => {
            buttons.forEach(btn => {
                btn.classList.remove('active');
                btn.setAttribute('aria-selected', 'false');
            });
            panels.forEach(panel => panel.classList.remove('active'));

            button.classList.add('active');
            button.setAttribute('aria-selected', 'true');
            
            const targetId = button.getAttribute('data-tab-target');
            const targetPanel = container.querySelector(`#${targetId}`);
            if (targetPanel) {
                targetPanel.classList.add('active');
            }
        });

        button.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                button.click();
            }
        });
    });
}

function initTabsSettings() {
    const tabsList = document.getElementById('tabs-list');
    const btnAddTab = document.getElementById('btn-add-tab');
    const template = document.getElementById('tab-item-template');
    const positionSelect = document.getElementById('position');
    const styleField = document.getElementById('style-field');

    if (!tabsList || !btnAddTab || !template) return;

    if (btnAddTab.hasAttribute('data-tabs-initialized')) return;
    btnAddTab.setAttribute('data-tabs-initialized', 'true');

    if (positionSelect && styleField) {
        positionSelect.addEventListener('change', function() {
            if (this.value === 'left') {
                styleField.style.display = 'none';
            } else {
                styleField.style.display = 'block';
            }
        });
    }

    function updateTabIndices() {
        const items = tabsList.querySelectorAll('.tab-item');
        const emptyState = document.getElementById('tabs-empty');
        const tabsCount = document.querySelector('.tabs-count');

        items.forEach((item, index) => {
            item.dataset.index = index;
            const title = item.querySelector('.tab-title');
            if (title) {
                title.textContent = title.textContent.replace(/#\d+/, `#${index + 1}`);
            }

            const inputs = item.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/tabs\[\d+\]/, `tabs[${index}]`));
                }
            });
        });

        if (items.length === 0) {
            if (!emptyState) {
                const emptyDiv = document.createElement('div');
                emptyDiv.id = 'tabs-empty';
                emptyDiv.className = 'tabs-empty';
                emptyDiv.innerHTML = `
                    <p class="empty-text">${window.tabsLang?.no_tabs_yet || 'No tabs created yet'}</p>
                    <p class="empty-subtext">${window.tabsLang?.add_first_tab || 'Click "Add Tab" to create your first tab'}</p>
                `;
                tabsList.appendChild(emptyDiv);
            }
            if (tabsCount) tabsCount.style.display = 'none';
        } else {
            if (emptyState) emptyState.remove();
            if (tabsCount) {
                tabsCount.style.display = 'flex';
                const countText = tabsCount.querySelector('.count-text');
                if (countText) {
                    countText.textContent = `${items.length} ${window.tabsLang?.tabs_total || 'tabs total'}`;
                }
            }
        }
    }

    btnAddTab.addEventListener('click', function () {
        const itemCount = tabsList.querySelectorAll('.tab-item').length;
        const nextIndex = itemCount;
        const uniqId = Date.now();

        const templateContent = template.innerHTML
            .replace(/{index}/g, nextIndex)
            .replace(/{number}/g, nextIndex + 1)
            .replace(/{uniqid}/g, uniqId);

        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = templateContent;
        const tabItem = tempDiv.firstElementChild;

        const emptyState = document.getElementById('tabs-empty');
        if (emptyState) emptyState.remove();

        tabsList.appendChild(tabItem);
        updateTabIndices();

        setTimeout(() => {
            const newEditor = tabItem.querySelector('textarea[name*="content"]');
            if (newEditor && window.initEditor) {
                window.initEditor(newEditor);
            }
        }, 100);

        const titleInput = tabItem.querySelector('input[name*="title"]');
        if (titleInput) {
            titleInput.focus();
        }
    });

    tabsList.addEventListener('click', function (e) {
        const removeBtn = e.target.closest('.btn-remove-tab');
        if (removeBtn) {
            const tabItem = removeBtn.closest('.tab-item');
            if (tabItem) {
                removeTabItemWithAnimation(tabItem);
            }
        }
    });

    updateTabIndices();
}

function removeTabItemWithAnimation(tabItem) {
    tabItem.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
    tabItem.style.opacity = '0';
    tabItem.style.transform = 'translateX(-10px)';
    tabItem.style.maxHeight = tabItem.offsetHeight + 'px';

    setTimeout(() => {
        tabItem.style.maxHeight = '0';
        tabItem.style.marginBottom = '0';
        tabItem.style.paddingTop = '0';
        tabItem.style.paddingBottom = '0';
    }, 150);

    setTimeout(() => {
        tabItem.remove();
        const tabsList = document.getElementById('tabs-list');
        if (tabsList) {
            const items = tabsList.querySelectorAll('.tab-item');
            const emptyState = document.getElementById('tabs-empty');
            const tabsCount = document.querySelector('.tabs-count');

            items.forEach((item, index) => {
                item.dataset.index = index;
                const title = item.querySelector('.tab-title');
                if (title) {
                    title.textContent = title.textContent.replace(/#\d+/, `#${index + 1}`);
                }

                const inputs = item.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    const name = input.getAttribute('name');
                    if (name) {
                        input.setAttribute('name', name.replace(/tabs\[\d+\]/, `tabs[${index}]`));
                    }
                });
            });

            if (items.length === 0) {
                if (!emptyState) {
                    const emptyDiv = document.createElement('div');
                    emptyDiv.id = 'tabs-empty';
                    emptyDiv.className = 'tabs-empty';
                    emptyDiv.innerHTML = `
                        <p class="empty-text">${window.tabsLang?.no_tabs_yet || 'No tabs created yet'}</p>
                        <p class="empty-subtext">${window.tabsLang?.add_first_tab || 'Click "Add Tab" to create your first tab'}</p>
                    `;
                    tabsList.appendChild(emptyDiv);
                }
                if (tabsCount) tabsCount.style.display = 'none';
            } else {
                if (emptyState) emptyState.remove();
                if (tabsCount) {
                    tabsCount.style.display = 'flex';
                    const countText = tabsCount.querySelector('.count-text');
                    if (countText) {
                        countText.textContent = `${items.length} ${window.tabsLang?.tabs_total || 'tabs total'}`;
                    }
                }
            }
        }
    }, 300);
} 