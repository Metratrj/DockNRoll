<?php
/** @var \OpenAPI\Client\Model\ImageSummary $image */

$shortId = substr($image->getId(), 7, 12); $repoTag = $image->getRepoTags() && $image->getRepoTags()[0] ?
htmlspecialchars($image->getRepoTags()[0]) : '&lt;none&gt;'; $size = round($image->getSize() / (1024 * 1024), 2) . '
MB'; ?>

<tr>
    <td class="size-px whitespace-nowrap">
        <div class="py-2 ps-6">
            <label class="flex">
                <input type="checkbox" class="dynamic-table-checkbox" />
                <span class="sr-only">Checkbox</span>
            </label>
        </div>
    </td>
    <td class="size-px whitespace-nowrap">
        <div class="py-2 pe-6">
            <a href="/images/<?= $image->getId() ?>" class="block text-sm font-medium text-[#465fff] underline">
                <?= $shortId ?>
            </a>
        </div>
    </td>
    <td class="size-px whitespace-nowrap">
        <div class="py-2 pe-6"><?= $repoTag ?></div>
    </td>
    <td class="size-px whitespace-nowrap">
        <div class="px-6 py-2"><?= $size ?></div>
    </td>
    <td class="size-px whitespace-nowrap">
        <div class="flex justify-end px-6 py-2">
            <div
                class="group inline-flex items-center divide-x divide-gray-800 rounded-lg border border-gray-800 shadow-2xs transition-all">
                <div class="inline-block">
                    <a
                        class="inline-flex items-center justify-center gap-x-2 rounded-s-md px-2 py-1.5 text-sm font-semibold text-gray-400 shadow-2xs"
                        href="/images/<?= $shortId ?>"
                        >View</a
                    >
                </div>
            </div>
        </div>
    </td>
</tr>
