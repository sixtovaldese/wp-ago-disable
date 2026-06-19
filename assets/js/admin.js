(function () {
    'use strict';

    var toggles = document.querySelectorAll('.ago-switch input[type="checkbox"]');
    var enableAll = document.getElementById('ago-enable-all');
    var disableAll = document.getElementById('ago-disable-all');
    var applyRecommended = document.getElementById('ago-apply-recommended');
    var statusBox = document.getElementById('ago-disable-status');
    var saveTimer = null;

    if (!toggles.length) return;

    var settings = (typeof agodisableData !== 'undefined') ? agodisableData.settings : {};
    toggles.forEach(function (toggle) {
        var key = toggle.getAttribute('data-key');
        if (key && settings[key]) toggle.checked = true;
    });

    toggles.forEach(function (toggle) {
        toggle.addEventListener('change', function () {
            clearTimeout(saveTimer);
            saveTimer = setTimeout(saveSettings, 400);
        });
    });

    if (enableAll) {
        enableAll.addEventListener('click', function () {
            toggles.forEach(function (t) { t.checked = true; });
            saveSettings();
        });
    }

    if (disableAll) {
        disableAll.addEventListener('click', function () {
            toggles.forEach(function (t) { t.checked = false; });
            saveSettings();
        });
    }

    if (applyRecommended) {
        applyRecommended.addEventListener('click', function () {
            toggles.forEach(function (t) {
                t.checked = t.getAttribute('data-recommended') === '1';
            });
            saveSettings();
        });
    }

    function saveSettings() {
        var data = {};
        toggles.forEach(function (toggle) {
            var key = toggle.getAttribute('data-key');
            if (key) data[key] = toggle.checked;
        });

        if (statusBox) {
            statusBox.style.display = 'block';
            statusBox.className = '';
            statusBox.textContent = 'Saving…';
        }

        fetch(agodisableData.restUrl + '/settings', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': agodisableData.nonce },
            body: JSON.stringify(data),
        })
        .then(function (r) { return r.json(); })
        .then(function (resp) {
            if (!statusBox) return;
            if (resp.saved) {
                statusBox.className = 'success';
                statusBox.textContent = 'Settings saved. Reload the frontend to see changes.';
            } else {
                statusBox.className = 'error';
                statusBox.textContent = 'Could not save settings.';
            }
            setTimeout(function () { statusBox.style.display = 'none'; }, 3000);
        })
        .catch(function (err) {
            if (!statusBox) return;
            statusBox.className = 'error';
            statusBox.textContent = 'Error: ' + err.message;
        });
    }
})();
