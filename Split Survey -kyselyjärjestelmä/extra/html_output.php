<html><head></head><body><form id="form_Add_rating" action="" method="post"><table id="table_Add_rating" border="0"> <!-- Add_rating == 'Add' or 'Edit' _tableName -->
			<tbody><tr>
				<td class="tableTopBar" colspan="2">Add Rating</td>
			</tr>
			<tr>
				<td class="tableBar" colspan="2">&nbsp;</td>
			</tr><tr>
				<td><label for="name">Name</label></td> <!-- <label for= "name">Name</label> == the label -->
				<td><input name="name" value="" type="text"></td> <!-- <input    name="name" type="text" value="" /> == the field -->
			</tr><tr></tr><tr>
				<td><label for="description">Description</label></td> <!-- <label for= "description">Description</label> == the label -->
				<td><input name="description" value="" type="text"></td> <!-- <input    name="description" type="text" value="" /> == the field -->
			</tr><tr></tr><tr>
				<td><label for="long_desc">Long Desc</label></td> <!-- <label for= "long_desc">Long Desc</label> == the label -->
				<td><textarea name="long_desc" cols="35" rows="8" value=""></textarea></td> <!-- <textarea name="long_desc" cols="35" rows="8" value=""></textarea> == the field -->
			</tr><tr></tr><tr>
					<td><input name="submit" value="Add Rating" type="submit"></td> <!-- Add Rating == Submit Name -->
					<td><input name="reset" value="Reset" type="reset"></td>   <!-- Reset == Reset Name, useless unless php code is changed (will always be 'Reset') -->
				</tr>
			</tbody></table></form></body></html>