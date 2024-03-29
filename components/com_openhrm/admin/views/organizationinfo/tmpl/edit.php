<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ngoc Nha
 * Date: 4/6/13
 * Time: 11:20 AM
 * To change this template use File | Settings | File Templates.
 */
// No direct access to this file
defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
	Joomla.orderTable = function () {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php //echo $listOrder; ?>') {
			dirn = 'asc';
		}
		else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<div class="main-container">
	<script type="text/javascript">
		Joomla.submitbutton = function (task) {
			if (task != 'country.close' || document.formvalidator.isValid(document.id('itemForm'))) {
				Joomla.submitform(task, document.getElementById('itemForm'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
			}
		}
	</script>
	<form enctype="multipart/form-data"
		action="<?php JRoute::_('index.php?option=com_openhrm&view=organizationinfo'); ?>" method="post" name="itemForm" id="itemForm"
		class="form-validate form-horizontal">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#general" data-toggle="tab"><?php echo JText::_('General')?></a></li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane active" id="general">
				<fieldset class="adminform">
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('name'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('name'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('tax_code'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('tax_code'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('registration_number'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('registration_number'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('phone'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('phone'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('fax'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('fax'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('email'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('email'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('country_id'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('country_id'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('state_id'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('state_id'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('city'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('city'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('zip_code'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('zip_code'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('street1'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('street1'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('street2'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('street2'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('logo'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('logo'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('note'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('note'); ?>
						</div>
					</div>
				</fieldset>
			</div>
		</div>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php if (isset($this->item->id))	{	echo $this->item->id;	} ?>" />
		<input type="hidden" name="jform[id]" value="<?php if (isset($this->item->id))	{	echo $this->item->id;	} ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
