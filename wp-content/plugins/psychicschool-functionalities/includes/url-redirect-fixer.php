<?php
!defined('ABSPATH') && exit;

if (function_exists('cuf_save_mappings')) {

    return;
}


// ======================
// ADMIN MENU
// ======================
add_action('admin_menu', function () {
    add_menu_page(
        'Classroom URL Fixer',
        'URL Fixer',
        'manage_options',
        'classroom-url-fixer',
        'cuf_settings_page',
        'dashicons-randomize'
    );
});

// ======================
// SAVE DATA
// ======================
function cuf_save_mappings()
{
    if (!isset($_POST['cuf_nonce']) || !wp_verify_nonce($_POST['cuf_nonce'], 'cuf_save')) return;

    $rows = $_POST['rows'] ?? [];

    $clean = [];

    foreach ($rows as $row) {
        $old = sanitize_title($row['old']);
        $new = esc_url_raw($row['new']);

        if (!empty($old) && !empty($new)) {
            $clean[$old] = $new;
        }
    }

    update_option('cuf_mappings', $clean);
}

// ======================
// SETTINGS PAGE
// ======================
function cuf_settings_page()
{

    if (isset($_POST['cuf_save'])) {
        cuf_save_mappings();
        echo '<div class="updated"><p>Saved!</p></div>';
    }

    $mappings = get_option('cuf_mappings', []);

?>

    <div class="wrap">
        <h1>Classroom URL Fixer</h1>

        <form method="post">
            <?php wp_nonce_field('cuf_save', 'cuf_nonce'); ?>

            <table class="widefat" id="cuf-table">
                <thead>
                    <tr>
                        <th style="width:40%">Old Slug</th>
                        <th style="width:50%">Target URL</th>
                        <th style="width:10%">Action</th>
                    </tr>
                </thead>

                <tbody id="cuf-rows">

                    <?php if (!empty($mappings)): ?>
                        <?php
                        $cuf_row = 0;
                        foreach ($mappings as $old => $new) :
                        ?>
                            <tr>
                                <td><input type="text" name="rows[<?php echo (int) $cuf_row; ?>][old]" value="<?php echo esc_attr($old); ?>" class="widefat"></td>
                                <td><input type="text" name="rows[<?php echo (int) $cuf_row; ?>][new]" value="<?php echo esc_attr($new); ?>" class="widefat"></td>
                                <td><button type="button" class="button cuf-remove">X</button></td>
                            </tr>
                        <?php
                            ++$cuf_row;
                        endforeach;
                        ?>
                    <?php endif; ?>

                </tbody>
            </table>

            <p>
                <button type="button" class="button button-secondary" id="cuf-add">+ Add Row</button>
            </p>

            <p>
                <button type="submit" name="cuf_save" class="button button-primary">Save</button>
            </p>
        </form>
    </div>

    <script>
        function cufNextRowIndex() {
            let max = -1;
            document.querySelectorAll('#cuf-rows [name^="rows["]').forEach(function(el) {
                const m = el.name.match(/^rows\[(\d+)\]/);
                if (m) max = Math.max(max, parseInt(m[1], 10));
            });
            return max + 1;
        }
        document.getElementById('cuf-add').addEventListener('click', function() {
            const i = cufNextRowIndex();
            const row = `
        <tr>
            <td><input type="text" name="rows[${i}][old]" placeholder="old-slug" class="widefat"></td>
            <td><input type="text" name="rows[${i}][new]" placeholder="/classroom/new-slug/" class="widefat"></td>
            <td><button type="button" class="button cuf-remove">X</button></td>
        </tr>`;
            document.getElementById('cuf-rows').insertAdjacentHTML('beforeend', row);
        });

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.cuf-remove');
            if (!btn) return;
            if (!window.confirm('Remove this row? It will be deleted after you click Save.')) return;
            btn.closest('tr').remove();
        });
    </script>

<?php
}

// ======================
// REDIRECT LOGIC
// ======================
add_action('template_redirect', function () {

    if (is_admin()) return;

    $mappings = get_option('cuf_mappings', []);

    if (empty($mappings)) return;

    $current_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    // only root-level slug
    if (substr_count($current_path, '/') > 0) return;

    if (isset($mappings[$current_path])) {

        $target = $mappings[$current_path];

        // Full URL vs path: home_url() must not wrap an absolute URL (breaks the link).
        if (preg_match('#^https?://#i', $target)) {
            $redirect_to = $target;
        } else {
            $redirect_to = home_url($target);
        }

        $target_path = trim((string) parse_url($redirect_to, PHP_URL_PATH), '/');

        // prevent loop (root slug must not redirect to itself)
        if ($current_path !== $target_path) {
            wp_safe_redirect($redirect_to, 301);
            exit;
        }
    }
});
