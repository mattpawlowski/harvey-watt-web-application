<form id="pageForm" name="pageForm" method="post" action="/admin/extras/menus/save/<? echo $menu_id; ?>">
    <input type="hidden" name="menu_id" id="menu_id" value="<? echo $menu_id; ?>">
	<div class="form-row">
    <div class="input-wrapper">
    <label for="menu_name">Menu Name <img src="/images/app/icons/help.png" class="help-icon help" data-subject="menu-name"></label>
    <input type="text" name="menu_name" id="menu_name" value="<? echo $menu_name; ?>" class="input-full" />
    </div>
    </div>
    
    <!-- --------------------- -->

	<div class="form-row">
    <div class="input-wrapper">
    <label for="menu_slug">Menu Slug <img src="/images/app/icons/help.png" class="help-icon help" data-subject="menu-slug"></label>
    <input type="text" name="menu_slug" id="menu_slug" value="<? echo $menu_slug; ?>" class="input-full">
    </div>
    </div>
    
    <!-- --------------------- -->
</form>