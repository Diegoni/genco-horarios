<a href="#" class="scrollup">Scroll</a>
<div id="footer">
	<table width="100%">
	<tbody>
		<tr>
		<td class="left">
			<span class="copyright">
			<?php
			$ar=fopen("version.txt","r") or
			die("No se pudo abrir el archivo");
			//while (!feof($ar))
			//{
				$linea=fgets($ar);
				$lineasalto=nl2br($linea);
				echo $lineasalto;
			//}
			fclose($ar);
			?>
			</span>
		</td>
		<td align="right">
			<a href="http://www.tmsgroup.com.ar/" title="Sitio de TMS Group" target="_blank">
			<span class="copyright">Desarrollado por TMS Group</span>
			</a>
		</td>
		</tr>
	</tbody>
	</table>
</div>