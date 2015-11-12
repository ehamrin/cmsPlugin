<table>
    <tr>
        <th>Page</th>
        <th>Slug</th>
        <th>View</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
<?php foreach($this->model->FetchAll() as $page): ?>
    <tr>
        <td><?= $page->GetName(); ?></td>
        <td><?= $page->GetSlug(); ?></td>
        <td><a href="/<?= $page->GetSlug(); ?>" target="_blank" class="edit"><span class="fa fa-eye"></span></a></td>
        <td><a href="/admin/page/edit/<?= $page->GetID(); ?>" class="edit"><i class="fa fa-pencil-square-o"></i></a></td>
        <td><?= ($page->GetID() > 1 ? '<a href="/admin/page/delete/' . $page->GetID() . '" class="delete"><i class="fa fa-trash"></i></a>' : ''); ?></td>
    </tr>
<?php endforeach; ?>
</table>