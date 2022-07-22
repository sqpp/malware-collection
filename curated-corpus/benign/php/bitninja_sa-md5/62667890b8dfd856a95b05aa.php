<?php if(!defined("__XE__"))exit;
$__tpl=TemplateHandler::getInstance();echo $__tpl->compile('modules/page/tpl','header.html') ?>
<?php if($__Context->XE_VALIDATOR_MESSAGE && $__Context->XE_VALIDATOR_ID == 'modules/page/tpl/page_info/1'){ ?><div class="message <?php echo $__Context->XE_VALIDATOR_MESSAGE_TYPE ?>">
	<p><?php echo $__Context->XE_VALIDATOR_MESSAGE ?></p>
</div><?php } ?>
<section class="section">
<?php Context::addJsFile("modules/page/ruleset/updatePage.xml", FALSE, "", 0, "body", TRUE, "") ?><form  action="./" method="post" enctype="multipart/form-data" class="x_form-horizontal"><input type="hidden" name="error_return_url" value="<?php echo htmlspecialchars(getRequestUriByServerEnviroment(), ENT_COMPAT | ENT_HTML401, 'UTF-8', false) ?>" /><input type="hidden" name="mid" value="<?php echo $__Context->mid ?>" /><input type="hidden" name="vid" value="<?php echo $__Context->vid ?>" /><input type="hidden" name="ruleset" value="updatePage" />
	<input type="hidden" name="module" value="page" />
	<input type="hidden" name="act" value="procPageAdminUpdate" />
	<input type="hidden" name="page" value="<?php echo $__Context->page ?>" />
	<input type="hidden" name="module_srl" value="<?php echo $__Context->module_srl ?>" />
	<input type="hidden" name="success_return_url" value="<?php echo getRequestUriByServerEnviroment() ?>" />
	<input type="hidden" name="xe_validator_id" value="modules/page/tpl/page_info/1" />
	<div class="x_control-group">
		<label class="x_control-label"><?php echo $__Context->lang->page_type ?></label>
		<div class="x_controls" style="padding-top:4px"><?php echo $__Context->lang->page_type_name[$__Context->module_info->page_type] ?></div>
	</div>
	<div class="x_control-group">
		<label class="x_control-label" for="page_name"><?php echo $__Context->lang->mid ?></label>
		<div class="x_controls">
			<input type="text" name="page_name" id="page_name" value="<?php echo $__Context->module_info->mid ?>" />
			<a href="#aboutMid" class="x_icon-question-sign" data-toggle><?php echo $__Context->lang->help ?></a>
			<p class="x_help-block" id="aboutMid" hidden><?php echo $__Context->lang->about_mid ?></p>
		</div>
	</div>
	<div class="x_control-group">
		<label class="x_control-label" for="module_category_srl"><?php echo $__Context->lang->module_category ?></label>
		<div class="x_controls">
			<select name="module_category_srl" id="module_category_srl">
				<option value="0"><?php echo $__Context->lang->notuse ?></option>
				<?php if($__Context->module_category&&count($__Context->module_category))foreach($__Context->module_category as $__Context->key=>$__Context->val){ ?><option value="<?php echo $__Context->key ?>"<?php if($__Context->module_info->module_category_srl==$__Context->key){ ?> selected="selected"<?php } ?>><?php echo $__Context->val->title ?></option><?php } ?>
			</select>
			<a href="#aboutCategory" class="x_icon-question-sign" data-toggle><?php echo $__Context->lang->help ?></a>
			<p class="x_help-block" id="aboutCategory" hidden><?php echo $__Context->lang->about_module_category ?></p>
		</div>
	</div>
	<div class="x_control-group">
		<label class="x_control-label" for="lang_browser_title"><?php echo $__Context->lang->browser_title ?></label>
		<div class="x_controls">
			<input type="text" name="browser_title" id="browser_title" value="<?php if(strpos($__Context->module_info->browser_title, '$user_lang->') === false){;
echo $__Context->module_info->browser_title;
}else{;
echo htmlspecialchars($__Context->module_info->browser_title, ENT_COMPAT | ENT_HTML401, 'UTF-8', false);
} ?>" class="lang_code" />
		</div>
	</div>
	<div class="x_control-group">
		<label class="x_control-label" for="layout_srl"><?php echo $__Context->lang->layout ?></label>
		<div class="x_controls">
			<select name="layout_srl" id="layout_srl" style="width:auto">
				<option value="0"><?php echo $__Context->lang->notuse ?></option>
				<?php if($__Context->layout_list&&count($__Context->layout_list))foreach($__Context->layout_list as $__Context->key=>$__Context->val){ ?><option value="<?php echo $__Context->val->layout_srl ?>"<?php if($__Context->module_info->layout_srl==$__Context->val->layout_srl){ ?> selected="selected"<?php } ?>><?php echo $__Context->val->title ?>(<?php echo $__Context->val->layout ?>)</option><?php } ?>
			</select>
			<a href="#aboutLayout" class="x_icon-question-sign" data-toggle><?php echo $__Context->lang->help ?></a>
			<p class="x_help-block" id="aboutLayout" hidden><?php echo $__Context->lang->about_layout ?></p>
		</div>
	</div>
	<div class="x_control-group">
		<label class="x_control-label"><?php echo $__Context->lang->mobile_view ?></label>
		<div class="x_controls">
			<label for="use_mobile">
				<input type="checkbox" name="use_mobile" id="use_mobile" value="Y"<?php if($__Context->module_info->use_mobile == 'Y'){ ?> checked="checked"<?php } ?> />
				<?php echo $__Context->lang->about_mobile_view ?>
			</label>
		</div>
	</div>
	<div class="x_control-group">
		<label class="x_control-label" for="mlayout_srl"><?php echo $__Context->lang->mobile_layout ?></label>
		<div class="x_controls">
			<select name="mlayout_srl" id="mlayout_srl">
				<option value="0"><?php echo $__Context->lang->notuse ?></option>
				<?php if($__Context->mlayout_list&&count($__Context->mlayout_list))foreach($__Context->mlayout_list as $__Context->key=>$__Context->val){ ?><option value="<?php echo $__Context->val->layout_srl ?>"<?php if($__Context->module_info->mlayout_srl==$__Context->val->layout_srl){ ?> selected="selected"<?php } ?>><?php echo $__Context->val->title ?>(<?php echo $__Context->val->layout ?>)</option><?php } ?>
			</select>
			<a href="#aboutMobileLayout" class="x_icon-question-sign" data-toggle><?php echo $__Context->lang->help ?></a>
			<p class="x_help-block" id="aboutMobileLayout" hidden><?php echo $__Context->lang->about_layout ?></p>
		</div>
	</div>
	<?php if($__Context->module_info->page_type != 'ARTICLE'){ ?><div class="x_control-group">
		<label class="x_control-label" for="page_caching_interval"><?php echo $__Context->lang->page_caching_interval ?></label>
		<div class="x_controls">
			<input type="text" name="page_caching_interval" id="page_caching_interval" value="<?php echo (int)$__Context->module_info->page_caching_interval ?>"  /> <?php echo $__Context->lang->unit_min ?>
			<a href="#aboutCaching" class="x_icon-question-sign" data-toggle><?php echo $__Context->lang->help ?></a>
			<p class="x_help-block" id="aboutCaching" hidden><?php echo $__Context->lang->about_page_caching_interval ?></p>
		</div>
	</div><?php } ?>
	<?php if($__Context->module_info->page_type == 'OUTSIDE'){ ?><div class="x_control-group">
		<label class="x_control-label" for="path"><?php echo $__Context->lang->opage_path ?></label>
		<div class="x_controls">
			<input type="text" name="path" id="path" value="<?php echo $__Context->module_info->path ?>" />
			<a href="#aboutOpagePath" class="x_icon-question-sign" data-toggle><?php echo $__Context->lang->help ?></a>
			<p class="x_help-block" id="aboutOpagePath" hidden><?php echo $__Context->lang->about_opage_path ?><b><?php echo realpath("./") ?></b></p>
		</div>
	</div><?php } ?>
	<?php if($__Context->module_info->page_type == 'OUTSIDE'){ ?><div class="x_control-group">
		<label class="x_control-label" for="mpath"><?php echo $__Context->lang->opage_mobile_path ?></label>
		<div class="x_controls">
			<input type="text" name="mpath" id="mpath" value="<?php echo $__Context->module_info->mpath ?>"  />
			<a href="#aboutOpageMobilePath" class="x_icon-question-sign" data-toggle><?php echo $__Context->lang->help ?></a>
			<p class="x_help-block" id="aboutOpageMobilePath" hidden><?php echo $__Context->lang->about_opage_mobile_path ?><b><?php echo realpath("./") ?></b></p>
		</div>
	</div><?php } ?>
	<?php if($__Context->module_info->page_type == 'ARTICLE'){ ?><div class="x_control-group">
		<label class="x_control-label" for="skin"><?php echo $__Context->lang->skin ?></label>
		<div class="x_controls">
			<select name="skin" id="skin">
				<?php if($__Context->skin_list&&count($__Context->skin_list))foreach($__Context->skin_list as $__Context->key=>$__Context->val){ ?><option value="<?php echo $__Context->key ?>"<?php if($__Context->module_info->skin==$__Context->key ||(!$__Context->module_info->skin && $__Context->key=='default')){ ?> selected="selected"<?php } ?>><?php echo $__Context->val->title ?></option><?php } ?>
			</select>
			<a href="#aboutSkin" class="x_icon-question-sign" data-toggle><?php echo $__Context->lang->help ?></a>
			<p class="x_help-block" id="aboutSkin" hidden><?php echo $__Context->lang->about_skin ?></p>
		</div>
	</div><?php } ?>
	<?php if($__Context->module_info->page_type == 'ARTICLE'){ ?><div class="x_control-group optionnalData articleType">
		<label class="x_control-label" for="mskin"><?php echo $__Context->lang->mobile_skin ?></label>
		<div class="x_controls">
			<select name="mskin">
				<?php if($__Context->mskin_list&&count($__Context->mskin_list))foreach($__Context->mskin_list as $__Context->key=>$__Context->val){ ?><option value="<?php echo $__Context->key ?>"<?php if($__Context->module_info->mskin==$__Context->key ||(!$__Context->module_info->mskin && $__Context->key=='default')){ ?> selected="selected"<?php } ?>><?php echo $__Context->val->title ?></option><?php } ?>
			</select>
			<a href="#aboutMobileSkin" class="x_icon-question-sign" data-toggle><?php echo $__Context->lang->help ?></a>
			<p class="x_help-block" id="aboutMobileSkin" hidden><?php echo $__Context->lang->about_skin ?></p>
		</div>
	</div><?php } ?>
	<div class="x_clearfix btnArea">
		<div class="x_pull-right">
			<button type="submit" class="x_btn x_btn-primary"><?php echo $__Context->lang->cmd_save ?></button>
		</div>
	</div>
</form>
</section>
