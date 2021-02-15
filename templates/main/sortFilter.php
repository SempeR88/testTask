<form name="sort" id="sort" action="" method="GET">
   <div class="input-field col s3">
      <select name="sortType">
      		<?php foreach($sortOptions as $option): ?>
      			<?php 
      				$selected = '';
   	   			if ($sortParams['sortType'][0] == $option[0] && $sortParams['sortType'][1] == $option[1]) {
   	   				$selected = 'selected ';
   	   			}
      			?>
   	    <option <?= $selected ?>value="<?= $option[0] . '-' . $option[1] ?>"><?= $option[2] ?></option>
   		<?php endforeach; ?>
      </select>
   </div>
   <div class="input-field col s9">
      <i style="margin-top: 5px;" class="material-icons left">sort</i><input class="waves-effect waves-light btn blue lighten-1 sort-but" type="submit" value="Сортировать">
   </div>
</form>