<?php
session_start();
include_once "sql.php";

if (!isset($_SESSION["id"])) { header('Location: index.php'); }

$link			= bdd_connect();
$deck_id		= (isset($_POST["edit"]) && $_POST["id"] > 0) ? $_POST["id"] : 0;

if ($deck_id > 0)
{
	$sql		= "SELECT * FROM `magic_decks` WHERE `id` = ". $deck_id;
	$data		= mysql_fetch_array(mysql_query($sql));
	
	if ($data["account_id"] != $_SESSION["id"])
	{
		mysql_close();
		header('Location: index.php');
	}
	$deck		= $data["cards"];
	$deckname	= $data["title"];
	
} else { $deckname	= "Nom du deck"; }

mysql_close($link);
?>

<head>
	<title>Deck</title>
	<link rel="stylesheet" href="style.css" />
	<link rel="stylesheet" href="deck.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script type="text/javascript">
	
	var cards	= "";
	var deck 	= "<?=$deck?>";
	
	$(document).ready(function()
	{
		// getting cards
		$('input[name=cardname]')
			.attr('readonly', true)
			.addClass("dark");
		
		$.post("jpost.php", {action: "get_cards"}).done(function(data)
		{
			cards 	= data.split("|");
			$.each(cards, function(index, value) { cards[index]	= value.split("="); });
			$('input[name=cardname]')
				.attr('readonly', false)
				.removeClass("dark");
				
			if (deck.length > 0)
			{
				deck			= deck.split('|');
				for (var i = 0; i < deck.length; i++)
				{
					var tmp		= deck[i].split("=");
					var	title	= cards[tmp[0] - cards[0][0]][1];
					$("#deck").append(
						"<div class=\"card\" id=\""+ tmp[0] +"\">"+
							"<img src=\"http://gatherer.wizards.com/Handlers/Image.ashx?type=card&name="+ title.replace(" ", "%20") +"\" />"+
							"<input type=\"text\" value=\""+ tmp[1] +"\" />"+
							"<input type=\"button\" value=\"X\" />"+
							"<div>"+ title +"</div>"+
						"</div>"
					);
				}
				$(".card input[type=button]").click(function() { $(this).parent().remove(); });
				$(".card input[type=text]").keyup(function()
				{
					if ($(this).val().length > 0)
						$(this).val((parseInt($(this).val()) > 0) ? parseInt($(this).val()) : 1);
				});
				$(".card img").hover(
					function() { $("#card").attr("src", $(this).attr("src")); },
					function() { $("#card").attr("src", "http://gatherer.wizards.com/Handlers/Image.ashx?type=card&name="+ $("#card").attr("title").replace(" ", "%20")); });
			}
			
		});
	
		// change input texts
		$("input[name=cardname]").focus(function()
		{
			if ($(this).val() == "Nom de la carte")
			{
				$(this).val("");
				$(this).css("color", "#333");
			}
		});
		$("input[name=cardname]").blur(function()
		{
			if ($(this).val() == "")
			{
				$(this).val("Nom de la carte");
				$(this).css("color", "#F93");
			}
		});
		
		$("input[name=deckname]").focus(function()
		{
			if ($(this).val() == "Nom du deck")
			{
				$(this).val("");
				$(this).css("color", "#333");
			}
		});
		$("input[name=deckname]").blur(function()
		{
			if ($(this).val() == "")
			{
				$(this).val("Nom du deck");
				$(this).css("color", "#F93");
			}
		});
	
		// display result cards
		$("input[name=cardname]").keyup(function()
		{
			var count	= 0;
			$("input[name=count]").val("...");
			$("#result").empty();
			
			if ($(this).val().length > 0)
				for (var i = 0; i < cards.length; i++)
				// otpi a faire
					if ($(this).val().toLowerCase() == cards[i][1].substring(0, $(this).val().length).toLowerCase())
					{
						$("#result").append("<option value=\""+ cards[i][0] +"\">"+ cards[i][1] +"</option>");
						count	+= 1;
					}
			
			$("input[name=count]").val((count > 0) ? (count > 1) ? count +" résultats" : "1 résultat !" : "");
		});
		
		// display card
		$("#result").change(function()
		{
			$("#card").attr("src", "http://gatherer.wizards.com/Handlers/Image.ashx?type=card&name=0"); // load gif ?
			$("#result option:selected").each(function()
			{
				$("#card").attr("title", $(this).text());
				$("#card").attr("value", $(this).val());
				var img		= new Image();
				img.src		= "http://gatherer.wizards.com/Handlers/Image.ashx?type=card&name="+ $(this).text().replace(" ", "%20");
				$(img).load(function() { $("#card").attr("src", this.src); });
			});
		});
		
		// add card to deck
		$("#add").click(function()
		{
			$("#result option:selected").each(function()
			{
				if ($(".card#"+ $(this).val()).length)
				{
					$(".card#"+ $(this).val() +" input[type=text]").val(parseInt($(".card#"+ $(this).val() +" input[type=text]").val()) + 1);
					 return;
				}
				
				var card	= $("#card").attr("title").replace(" ", "%20");
				$("#deck").append(
					"<div class=\"card\" id=\""+ $(this).val() +"\">"+
						"<img src=\"http://gatherer.wizards.com/Handlers/Image.ashx?type=card&name="+ card +"\" />"+
						"<input type=\"text\" value=\"1\" />"+
						"<input type=\"button\" value=\"X\" />"+
						"<div>"+ $(this).text() +"</div>"+
					"</div>"
				);
				
				$(".card input[type=button]").click(function() { $(this).parent().remove(); });
				$(".card input[type=text]").keyup(function()
				{
					if ($(this).val().length > 0)
						$(this).val((parseInt($(this).val()) > 0) ? parseInt($(this).val()) : 1);
				});
				$(".card img").hover(
					function() { $("#card").attr("src", $(this).attr("src")); },
					function() { $("#card").attr("src", "http://gatherer.wizards.com/Handlers/Image.ashx?type=card&name="+ $("#card").attr("title").replace(" ", "%20")); });
			});
		});
		
		
		// submit deck
		$("form").submit(function()
		{
			var deck	= "";
			$(".card").each(function()
			{
				if (deck.length)	deck	+= "|";
				deck	+= $(this).attr("id") +"="+ $(this).children("input[type=text]")[0].value;
			});
			$("form input[name=deck]").val(deck);
			return true;
		});
		
	});
	</script>
</head>

<body>

	<div class="page">
	
		<a class="block" href="http://letakol.free.fr/fiboot/">
			<div class="header"></div>
		</a>
		
		<div class="content">
		
			<fieldset class="searchfield">
				<legend>Recherche</legend>
				<input type="text" name="cardname" value="Nom de la carte" />
				<input type="text" name="count" readonly="1" />
				<select size="17" id="result"></select>
			</fieldset>
			
			<fieldset class="cardfield">
				<legend>Carte</legend>
				<img id="card" src="http://gatherer.wizards.com/Handlers/Image.ashx?type=card&name=0" />
				<input type="button" id="add" value="Ajouter cette carte au deck" />
			</fieldset>
			
			<fieldset class="deckfield" id="deck">
				<legend>Deck</legend>
			</fieldset>
				
			<form method="post" action="decklist.php">
				<input type="text" name="deckname" maxlength="255" value="<?=$deckname?>" />
				<input type="hidden" name="deck" />
				<input type="hidden" name="id" value="<?=$deck_id?>" />
				<input type="submit" name="save" value="Sauvegarder" />
				<input type="submit" name="delete" value="Supprimer" />
				<a href="decklist.php"><input type="button" class="dark" value="Retour" /></a>
			</form>
			
		</div>
	
		<div class="footer">
			Created by <a href="mailto:fiboot89@gmail.com">FiBoot</a>
		</div>
		
	</div>

</body>

<!--