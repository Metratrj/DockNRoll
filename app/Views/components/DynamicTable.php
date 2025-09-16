<?php

namespace App\Views\components;

class DynamicTable extends Component
{
    protected string $id;
    protected array $columns;
    protected string $endpoint;

    public function __construct(string $id, array $columns, string $endpoint, array $attributes = [])
    {
        parent::__construct($attributes);
        $this->id = $id;
        $this->columns = $columns;
        $this->endpoint = $endpoint;
    }

    public function render(): string
    {
        $this->attributes['id'] = $this->id . '-wrapper';
        $this->attributes['class'] = 'dynamic-table-wrapper ' . ($this->attributes['class'] ?? '');
        $this->attributes['data-endpoint'] = $this->endpoint;
        $attributes = $this->buildAttributes();

        $headerHtml = '';
        foreach ($this->columns as $column) {
            $sortable = $column['sortable'] ?? true;
            $sortKey = $sortable ? "data-sort='{$column['sort_key']}'" : '';
            $headerHtml .= "
<th class=\"dynamic-table-header-cell\" scope=\"col\" {$sortKey}>
    <div class=\"dynamic-table-header-cell-content\">
        <span class=\"dynamic-table-header-label\">{$column['label']}</span>
    </div>
</th>
";
        }

        ob_start();
        include __DIR__ . '/table_search_header.php';
        $searchHeader = ob_get_clean();

        ob_start();
        include __DIR__ . '/table_pagination_footer.php';
        $paginationFooter = ob_get_clean();

        return "
<div {$attributes}>
    <div class=\"dynamic-table-card\">
        {$searchHeader}

        <div class=\"dynamic-table-spinner-wrapper\" id=\"{$this->id}-spinner\">
            <div class=\"dynamic-table-spinner\" role=\"status\" aria-label=\"loading\">
                <span class=\"sr-only\">Loading...</span>
            </div>
        </div>

        <table id=\"{$this->id}\" class=\"dynamic-table\" style=\"display: none\">
            <thead class=\"dynamic-table-header\">
                <tr>
                    <th class=\"dynamic-table-header-cell dynamic-table-header-cell-checkbox\" scope=\"col\">
                        <label class=\"flex\">
                            <input type=\"checkbox\" class=\"dynamic-table-checkbox\" />
                            <span class=\"sr-only\">Checkbox</span>
                        </label>
                    </th>
                    {$headerHtml}
                </tr>
            </thead>
            <tbody class=\"dynamic-table-body\"></tbody>
        </table>

        {$paginationFooter}
    </div>
</div>
<script src=\"/js/dynamic-table.js\"></script>
";
    }
}
