<?php

return [
    'home' => 'Home',
    'forums' => 'Forums',
    'categories' => 'Categories',
    'positioning' => 'Positioning',

    'save' => 'Save',

    'all' => 'All',
    'active' => 'Active',
    'trashed' => 'Trashed',

    'create-forum' => 'Create Forum',
    'create-category' => 'Create Category',

    'edit-forum' => 'Edit Forum',
    'edit-category' => 'Edit Category',

    'view' => 'View',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'restore' => 'Restore',

    'admin-panel' => 'Administration Panel',

    'category-needed' => 'You need at least one category.',

    'positions-success' => 'Positions have been successfully updated. Reload the page.',

    'parent-deleted' => 'You cannot restore this forum because its parent forum is deleted.',
    'category-deleted' => 'You cannot restore this forum because its category is deleted.',

    'info1' => 'When you create a forum or a category, it automatically takes last position in a table. '
             . 'They can be reorganized only via "Positioning" page.',
    'info2' => 'Nothing can be permanently deleted. Everything that you delete goes to the trashcan and '
             . 'it can be restored at any time. Deleted stuff is visible only by admin panel. ',
    'info3' => 'If you delete a category, all forums that belong to that category are also deleted. '
             . 'They will not be automatically restored if you restored their category. Similar thing '
             . 'goes for deleting a parent forum.',
    'info4' => 'Only way to move forum to another category is via "Positioning" page. If '
             . 'you move forum to a deleted category, then it also gets deleted. Similar thing '
             . 'goes for deleted parent forums.',
];
