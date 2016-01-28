<h1>Listing all pages</h1>
<div class="inline-3-4">

    <div class="table-wrapper">
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
    </div>
</div>
<?php if($this->application->GetUser() && $this->application->GetUser()->Can('manage-widget')): ?>
<div class="inline-1-4">
    <form>
<?php foreach($widgets as $template => $widgets): ?>
         <h3><?= ucfirst($template) ?> template</h3>
        <fieldset class="widget-list">
            <legend>Widgets</legend>
    <?php foreach($widgets as $widgetHolder): ?>
            <p><?= $widgetHolder ?>:</p>
            <select>
                <option value="0">Choose widget</option>
        <?php foreach($this->application->GetWidget() as $name => $widget): ?>
                <option value="<?= $name ?>"><?= $name ?></option>
        <?php endforeach; ?>
            </select>

    <?php endforeach; ?>
        </fieldset>
<?php endforeach; ?>
     </form>
</div>
<?php endif; ?>