document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("search-command");
    const searchModal = document.getElementById("search-modal");
    const searchResultsContainer = document.getElementById("search-results");
    const commandSuggestionsContainer = document.getElementById("command-suggestions");

    let selectedIndex = -1;
    let currentResults = [];
    let knownCommands = [];
    let suggestionIndex = -1;

    if (!searchInput || !searchModal || !searchResultsContainer) {
        return;
    }

    const fetchCommands = async () => {
        try {
            const response = await fetch("/api/commands");
            if (!response.ok) throw new Error("Could not fetch commands.");
            knownCommands = await response.json();
        } catch (error) {
            console.error(error);
            knownCommands = [
                { name: "start", description: "Start a container." },
                { name: "stop", description: "Stop a container." },
            ];
        }
    };

    searchModal.style.display = "none";

    const debounce = (func, delay) => {
        let timeoutId;
        return (...args) => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => func.apply(this, args), delay);
        };
    };

    const showLoadingIndicator = (container, message) => {
        container.innerHTML = `<div class="p-4 text-gray-400">${message}</div>`;
    };

    const executeCommand = async (command, target) => {
        showLoadingIndicator(searchResultsContainer, `Executing '${command}' on '${target}'...`);
        commandSuggestionsContainer.innerHTML = "";

        try {
            const response = await fetch("/api/command", {
                method: "POST",
                headers: { "Content-Type": "application/json", Accept: "application/json" },
                body: JSON.stringify({ command, target }),
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.error || "Command failed");
            searchResultsContainer.innerHTML = `<div class="p-4 text-green-400">Success: ${result.message}</div>`;
            setTimeout(() => {
                searchModal.style.display = "none";
                searchInput.value = "";
            }, 1500);
        } catch (error) {
            searchResultsContainer.innerHTML = `<div class="p-4 text-red-400">Error: ${error.message}</div>`;
        }
    };

    const tryExecuteCommand = (inputValue) => {
        const commandNames = knownCommands.map((c) => c.name);
        const parts = inputValue.trim().split(/\s+/g);
        const command = parts[0].toLowerCase();
        const target = parts.slice(1).join(" ");
        if (commandNames.includes(command) && target) {
            executeCommand(command, target);
            return true;
        }
        return false;
    };

    const onInput = (e) => {
        const value = e.target.value;
        const parts = value.trim().split(/\s+/g);
        const command = parts[0].toLowerCase();
        const targetPart = parts.slice(1).join(" ");
        const commandNames = knownCommands.map((c) => c.name);

        suggestionIndex = -1;

        if (commandNames.includes(command) && value.includes(" ")) {
            commandSuggestionsContainer.innerHTML = "";
            searchResultsContainer.innerHTML = "";
            performSearch(targetPart, "Container", true);
        } else {
            if (parts.length === 1 && !value.endsWith(" ")) {
                renderCommandSuggestions(value);
            } else {
                commandSuggestionsContainer.innerHTML = "";
            }
            performSearch(value, null, false);
        }
    };

    const performSearch = async (query, type = null, isSuggestion = false) => {
        const container = isSuggestion ? commandSuggestionsContainer : searchResultsContainer;
        if (!isSuggestion && query.length < 2) {
            container.innerHTML = "";
            currentResults = [];
            return;
        }

        showLoadingIndicator(container, "Searching...");

        try {
            const response = await fetch("/api/search", {
                method: "POST",
                headers: { "Content-Type": "application/json", Accept: "application/json" },
                body: JSON.stringify({ query, type }),
            });
            const results = await response.json();
            if (!response.ok) throw new Error(results.message || `HTTP error!`);

            if (isSuggestion) {
                renderTargetSuggestions(results);
            } else {
                renderResults(results);
            }
        } catch (error) {
            console.error(`Search failed: ${error.message}`);
            container.innerHTML = `<div class="p-4 text-red-400">Error: ${error.message}</div>`;
        }
    };

    const renderCommandSuggestions = (inputValue) => {
        const commandPart = inputValue.toLowerCase();
        const suggestions = knownCommands.filter((c) => c.name.startsWith(commandPart));
        if (suggestions.length > 0 && commandPart.length > 0) {
            let html = '<ul class="p-2">';
            suggestions.forEach((suggestion, index) => {
                html += `
                    <li class="suggestion-item p-2 rounded-md cursor-pointer flex justify-between items-center" data-index="${index}" data-type="command">
                        <span>${suggestion.name}</span>
                        <span class="text-gray-400 text-sm">${suggestion.description}</span>
                    </li>`;
            });
            html += "</ul>";
            commandSuggestionsContainer.innerHTML = html;
        }
    };

    const renderTargetSuggestions = (groups) => {
        currentResults = [];
        let html = '<ul class="p-2">';
        let itemIndex = 0;
        if (Object.keys(groups).length === 0) {
            commandSuggestionsContainer.innerHTML =
                '<div class="p-4 text-gray-400">No matching containers found.</div>';
            return;
        }
        for (const groupName in groups) {
            for (const item of groups[groupName]) {
                currentResults.push(item);
                const targetName = item.name ? (item.name.startsWith("/") ? item.name.substring(1) : item.name) : "";
                html += `
                    <li class="suggestion-item p-2 rounded-md cursor-pointer flex justify-between items-center" data-index="${itemIndex}" data-type="target">
                        <span>${targetName}</span>
                        <span class="text-gray-400 text-sm">${item.image}</span>
                    </li>`;
                itemIndex++;
            }
        }
        html += "</ul>";
        commandSuggestionsContainer.innerHTML = html;
    };

    const renderResults = (groups) => {
        selectedIndex = -1;
        currentResults = [];
        let html = "";
        let itemIndex = 0;
        if (Object.keys(groups).length === 0) {
            searchResultsContainer.innerHTML = '<div class="p-4 text-gray-400">No results found.</div>';
            return;
        }
        for (const groupName in groups) {
            html += `<div class="p-2"><h3 class="px-2 text-xs text-gray-400 uppercase tracking-wider">${groupName}</h3><ul class="mt-1">`;
            for (const item of groups[groupName]) {
                currentResults.push(item);
                let link = "#";
                let title = "Unknown";
                let subtitle = "";
                let buttons = "";
                const targetName = item.name ? (item.name.startsWith("/") ? item.name.substring(1) : item.name) : "";
                if (item.actions && item.actions.length > 0) {
                    buttons =
                        '<div class="flex items-center gap-2">' +
                        item.actions
                            .map(
                                (action) =>
                                    `<button class="action-btn" data-command="${action}" data-target="${targetName}">${action}</button>`,
                            )
                            .join("") +
                        "</div>";
                }
                if (item.type === "Container") {
                    link = `/containers/${item.id}`;
                    title = targetName;
                    subtitle = `State: ${item.state} | Image: ${item.image}`;
                } else if (item.type === "Image") {
                    link = "#";
                    title = item.repoTags.split(",")[0] || "[Untitled Image]";
                    subtitle = `Size: ${(item.size / 1024 / 1024).toFixed(2)} MB`;
                }
                html += `<li class="search-result-item flex items-center justify-between p-2 rounded-md" data-index="${itemIndex}">
                            <a href="${link}" class="flex-grow">
                                <div class="font-medium text-white">${title}</div>
                                <div class="text-sm text-gray-400">${subtitle}</div>
                            </a>
                            ${buttons}
                         </li>`;
                itemIndex++;
            }
            html += "</ul></div>";
        }
        searchResultsContainer.innerHTML = html;
    };

    const updateSuggestionSelection = () => {
        document.querySelectorAll(".suggestion-item").forEach((el, i) => {
            el.classList.toggle("bg-gray-800", i === suggestionIndex);
        });
    };

    const updateSelection = () => {
        document.querySelectorAll(".search-result-item").forEach((el, i) => {
            el.classList.toggle("bg-gray-800", i === selectedIndex);
        });
    };

    const debouncedOnInput = debounce(onInput, 300);
    searchInput.addEventListener("input", debouncedOnInput);

    searchInput.addEventListener("focus", () => {
        searchModal.style.display = "flex";
    });

    commandSuggestionsContainer.addEventListener("click", (e) => {
        const suggestionItem = e.target.closest(".suggestion-item");
        if (suggestionItem) {
            const suggestionType = suggestionItem.dataset.type;
            const suggestionText = suggestionItem.querySelector("span").textContent;
            const currentInput = searchInput.value;
            const parts = currentInput.trim().split(/\s+/g);
            if (suggestionType === "command") {
                searchInput.value = suggestionText + " ";
            } else if (suggestionType === "target") {
                parts[parts.length - 1] = suggestionText;
                searchInput.value = parts.join(" ") + " ";
            }
            searchInput.focus();
            commandSuggestionsContainer.innerHTML = "";
        }
    });

    searchResultsContainer.addEventListener("click", (e) => {
        const actionBtn = e.target.closest(".action-btn");
        if (actionBtn) {
            e.preventDefault();
            const command = actionBtn.dataset.command;
            const target = actionBtn.dataset.target;
            if (command && target) executeCommand(command, target);
        }
    });

    searchModal.addEventListener("click", (e) => {
        if (e.target === searchModal) searchModal.style.display = "none";
    });

    document.addEventListener("keydown", (e) => {
        if (searchModal.style.display !== "none") {
            const suggestions = commandSuggestionsContainer.querySelectorAll(".suggestion-item");
            if (suggestions.length > 0) {
                if (e.key === "ArrowDown") {
                    e.preventDefault();
                    if (suggestionIndex < suggestions.length - 1) suggestionIndex++;
                } else if (e.key === "ArrowUp") {
                    e.preventDefault();
                    if (suggestionIndex > 0) suggestionIndex--;
                } else if (e.key === "Enter") {
                    e.preventDefault();
                    if (suggestionIndex !== -1) {
                        suggestions[suggestionIndex].click();
                        return;
                    }
                }
                updateSuggestionSelection();
            } else {
                if (e.key === "ArrowDown") {
                    e.preventDefault();
                    if (selectedIndex < currentResults.length - 1) selectedIndex++;
                } else if (e.key === "ArrowUp") {
                    e.preventDefault();
                    if (selectedIndex > 0) selectedIndex--;
                } else if (e.key === "Enter") {
                    e.preventDefault();
                    if (tryExecuteCommand(searchInput.value)) return;
                    if (selectedIndex !== -1) {
                        const selectedElement = document.querySelector(`[data-index="${selectedIndex}"] a`);
                        if (selectedElement) selectedElement.click();
                    }
                }
                updateSelection();
            }
        }

        if ((e.metaKey || e.ctrlKey) && e.key === "k") {
            e.preventDefault();
            searchInput.focus();
        }
        if (e.key === "Escape") {
            searchModal.style.display = "none";
        }
    });

    fetchCommands();
});
