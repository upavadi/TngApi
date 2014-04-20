<p><h2><span style="color:#D77600; font-size:25px">Marriage Anniversaries for <?php echo $date->format('F Y'); ?></span></h2></p>
Clicking on a name takes you to the Individual's Family Page.</br>
<table class="form-table" border="1">
	<tbody>
			<th class="theader">Husband</th>
			<th class="theader">Wife</th>
			<th class="theader">Date</th>
			<th class="theader">Place</th>
			<th class="theader">Years</th>
	
<?php foreach ($manniversaries as $manniversary):?>
		<tr>
			<td><a href="/family/?personId=<?php echo $manniversary['personid1'];?>">
			<?php echo $manniversary['firstname1']; ?><?php echo $manniversary['lastname1']; ?></a></td>
			<td><a href="/family/?personId=<?php echo $manniversary['personid2'];?>">
			<?php echo $manniversary['firstname2']; ?><?php echo $manniversary['lastname2']; ?></a></td>
				
			<td><?php echo $manniversary['marrdate']; ?></a></td>
			
			
			
			<td><?php echo $manniversary['marrplace']; ?></td>
			<td><?php echo $manniversary['Years']; ?></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>
