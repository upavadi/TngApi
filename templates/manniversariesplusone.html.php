<h2>Marriage Anniversaries for <?php echo date('F Y', strtotime('+1 month')); ?></h2>
Clicking on a name takes you to the Individual's Family Page.</br>
<table cellspacing="1" cellpadding="1" border="1">
	<tbody>
			<th style="background-color: #EDEDED;">Husband</th>
			<th style="background-color: #EDEDED;">Wife</th>
			<th style="background-color: #EDEDED;">Date</th>
			<th style="background-color: #EDEDED;">Place</th>
			<th style="background-color: #EDEDED;">Years</th>
	
<?php foreach ($manniversariesplusone as $manniversary):
	var_dump($manniversariesplusone);
	?>
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
