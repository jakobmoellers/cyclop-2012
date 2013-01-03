<?php
/**
 * SERVICE: Users
 *
 * @version    0.2.12
 * @link       http://sumoam.sourceforge.net SUMO Access Manager
 * @author     Alberto Basso <albertobasso@users.sourceforge.net>
 * @copyright  Copyright &copy; 2003-2009, Alberto Basso
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package    SUMO
 *
 */

switch ($_GET['cmd'])
{
	// Get user image
	case 'GET_USER':

		$image = array(base64_decode('/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAYEBAQFBAYFBQYJBgUGCQsIBg'
									.'YICwwKCgsKCgwQDAwMDAwMEAwODxAPDgwTExQUExMcGxsbHCAgICAgICAg'
									.'ICD/2wBDAQcHBw0MDRgQEBgaFREVGiAgICAgICAgICAgICAgICAgICAgIC'
									.'AgICAgICAgICAgICAgICAgICAgICAgICAgICD/wAARCACAAIADAREAAhEB'
									.'AxEB/8QAHAABAAIDAQEBAAAAAAAAAAAAAAQFAQIDBgcI/8QAQhAAAQMDAg'
									.'MFAwkFBgcAAAAAAgEDBAAFERIhBhMxFDJBUWEVIoEHFiMzQlJxkaEkQ2KC'
									.'sjRjcsHw8XSSscLR0uH/xAAYAQEBAQEBAAAAAAAAAAAAAAAAAQIDBP/EAC'
									.'IRAQEBAAIBBAIDAAAAAAAAAAABEQISIRMxUWEDQSJxkf/aAAwDAQACEQMR'
									.'AD8A/VNAoFAoFAoNDfaBcEXvL0FN1/JN6YNUfJejJqnwT/qqVcGFkKneZc'
									.'T4IX9KrTBu28053CzjqnjUG9AoFAoFAoFAoFAoFBG5jj5KLK6WU2J7xXzQ'
									.'P/NUdm2W209xMea+K/itQb0Cg5usNubqmCTummxJ8aaOYOuNmjT/ANr6t1'
									.'Oi+i+S1RIqBQKBQKBQKBQKCLMMjMIgLgncq4SfZbTr+fRKsRurrbeGm9hH'
									.'bbworcXU8fzpg6VAoNCcROlXBzLlSAVo/H/WU9UoMQnjMCbd+uZXQ56+Rf'
									.'FKUSKgUCgUCgUCgUFUEpEdmyvFCRlr0QU/9iWt4jiD9aR07e2O2d/JKnU0'
									.'9pp4DTqaz7TT7v606ms9ubPbVv5LtTDWiyFFcou6UwdkeRLky4ndktKJJ6'
									.'guU/QlqfpVlWFKBQKBQKBQYLur+FB5h2RiK7/xTufh/vXaMK8p5FsK+7Ws'
									.'RuD2aDqj60BX1oORPetAG4Y90l286YLJl7U5bv8AG6if8v8A9rFaj0SLlE'
									.'WuTTNAoFAoFAoMF3V/Cg8dObLnTIniqjKaTz20Gn6JXbixVTXRG4uqlB0S'
									.'QvnUwFkL50waK6q1RoiERIibquyUF5bB1XFtpFyMJrCr/G6upf0SuXP2aj'
									.'1SJhESuTTNAoFAoFAoFB5riSK43pls7OsLqDPjnZRX/EldOFSqc2m5TKy4'
									.'uf75nHvAXjtXVhEqhQKAiKq4TrQTDTsDSGQ65rmeSwm6p/Ev+tqz7i+4cg'
									.'8tlDJdZmuszT7RLuvw8K5c63F9WFKBQKBQKBQauOA22Tji6QBMkS+CJQVk'
									.'Vsri8kyQOGA/srK/oZeq+HlW74REvdpTtHbbc4jFx/eCv1bqeTieC+S1eF'
									.'SqV2ZbSPl3RkrZKX7a/VEvoW4rXTyy6JaGXE1MTWjFfX/ep2XGCtkNhNUq'
									.'c0Ap1wtXsNWZrJKrdjjLKd6LMc2aH11L/lT+0egsNqYjKUh5ztFyc+tfLb'
									.'CfdbTwGuXO/wCNx2dD2Y4UltP2E1/aGk/dqv7wfT7yfGp7iyRUVEVFyi9F'
									.'rKs0CgUCgUCgprs72ua3bh3aDDsr139wF/FUytb4/KVKlSxYbRprv+K+VJ'
									.'BX85a0jVwxMFFxEIF6oSZSqK1yz2AyysZkV/g9z+lUq7UbM2mxNrkIrKqn'
									.'iSa/6s02ixR3CYTCInRKyrZHyRcouFpgs4kwH21BzvY95PBUrFi6j2s+zS'
									.'HbYS5FtObEVfFlV7v8i7fhinL5Is6ypQKBQKDBmIApkuBFMqtB5i0yVJt6'
									.'4ObHIJXvgWwJ8ARErtYwhyrsCGX2zXrWpBBcuT5/a0p5DVxHAnVLvEq/jT'
									.'BrqGgahoMo5jouKYOiS3U6Ol+a0wdmLrKZcExcXbwp1FrJuJo7DuGr3WTT'
									.'Uv8AdO+6XT8UX4VjF16uuLZQKBQKCq4neJqxS1HvGHLT+f3P+6tcPdK8zM'
									.'ecCIDAbD5+gp0rtGFXlK2M4oMUGcUDFAoFAoJw63bU81190hT8tqyPa2qQ'
									.'si2xn16uNga/zCi/51577uiXUCgUES7vux7VNfaXS60w4bZdcEIKqLvt1q'
									.'wfK3OMb+78m7ntSQhX1pLXMGVoAebFuD7JgaAiIHu5NpcD9nPjXacf5Mb4'
									.'a82636ZPebuTluiQ5LkOI0wDRaiYXSbjqugecnlEFMbJWkdLBNeuFv5kpB'
									.'SZHediy9HcVxg1BVH0LGaUQIIX+5SbuTF3KN2OacaNHVhg2tIttmmr3UcX'
									.'c/v0EeZf7tN4fhDa9MW/z33I2ERDFs4upX1TWioo/R4TPnVwTZMmbceGhv'
									.'sC4uwcQifWODbBjzBBSVC5oGWxJpXC1AjSZtu4aK+z7i7OzCF9I5tsAPMI'
									.'EJEHlABbkulMrQV3tq/ReD7z219PnBaNjfQA3RxBcbLTjR0PT08KueRKsl'
									.'1KRxC3Bj3j2vEKO47J5rbTRtEJCjehWwa1asrlMbVKFgvk+XxC8EkkW03D'
									.'n+xk0imOxny3N0TK8zOtM0sDh+7Py3y7VfygXUHy59iMGBDkga/RiJgjpK'
									.'TaZ5gn41bBZ8EcRyZkCGk3iqbGl882PZ7UFkmkRt8mmw5nZT6gKZXXXPnP'
									.'pY+t1xbKBQRrnGOVbZcZtURx9lxsFLpkxVEz186QeB4k+Ta4zuE+HoLD7L'
									.'dztUeJDnHkuU8wzyjcbRdOpfpGBIMp+Wa6zn5ZsUy2jiOy3KZFghFkRZzx'
									.'SI5SnTZ0OufWbiDiGiqmrGy5zXSXWcSLVaH7RDCK8XNkEZvSX8Y5jrpqZk'
									.'npldvSqK+Lb+KYcm59hSDyZ8opIPvG6phqAA3aEERcaPv0RwY4JYSZHSW6'
									.'T0OFHII6g6608Ul9xXJDxq0reNWyImVpomQOHpMKyXW0NmCxpCv+zckZKA'
									.'SB7rikirs4S75XamhP4dkzrJarO4YdmYVj2lgjFTBgO62ooi7uCm+U2pog'
									.'XXgZ8kuDdocQGblC7PIGU8+4vOA9TR6j5q40qSLTRKn2nim4PhMe7HFkwm'
									.'n0goy465l19tW8uGrYKgjnOEFd6DWP8n7cVm1u2pSbl2x1oldfffVkgxof'
									.'RBVTAdYqvdHr6U7GJR2PiLiJY8CZHhDEjyW3nLs0ZkZi0aHpZbJtNCrjCr'
									.'rxWbcXHqeFLTx5Yrc3bAjWt+ID77nPKVIFzQ/IN7udmVMojmO9XPlZWo9v'
									.'XNooFAoPDWu2LxZMvM66TJgx4s+Rb7dDiyXooNDEXlq79ATetwzQiyWcJX'
									.'S3GVb831vd9ulpuEuVIt3D4RozTaPG0bzrrKPE8+bKtkZaTFE8Oq4q7g8l'
									.'e/nCzFncPt3J9Ui3eFCZkoajI5MxAMBV3ZSVOZhc9a6SsusniIpEq2BIV2'
									.'BLaiXNm6NAqi32tgGkFUxt1XUPkhVcRXxrhOa4N7O/cnDn8u2zYslTXmuM'
									.'y32UdFTzqy04RCv8KpV/YmNR35N2vYvJeJfZ55stFAfdFkQRtstGBdbRFy'
									.'Sr0qC3CzuXm/XKFKmus2+zjHZCODxtK4480jxG4YEJlhCFETV1qaqFxZw/'
									.'EtPDcuWzfZ5zGtHJ/bHuhOiOMCeFwKruu9WUa8SQILDFv9kybqRPXCKwbx'
									.'TZWkgedQCHUp+KflSC1jWmVxFxBcoN1kPdgs6MMNQ47zrYmbrSOqbhgoma'
									.'oJCiZXzrF5YuI1w9rt26Xw2FyksPQ75brexcm3CF9Is5GzESMcKRAjioqr'
									.'1p9/Q9JbeI7jP4k4WYfcJmSDN0jXqGBKjay4qRxyopsqe9rbz9kqxZ4q69'
									.'/XNooFAoPLHwvfYNynSuHrozDYujnPlxJcZZIA+SIJvMqDrCopIKZEspmt'
									.'9vlManwnfYs72nabs2lxkMNsXXtkfmsySZ2B7Q0bKtmmpU2XGPDanYxwX5'
									.'OhKA025PV2eV0Yu8+aTafTOsmJaBBC+jHSCCKZXCedO5iLdvk2bk8SlfGp'
									.'Ks8yK7FejaNQkboaOdnUm+kRRUxvhKs5+DFXdvksjTbJabfzUZkWpGA7aL'
									.'W7wMoKGJDqTY1BF67LWp+ROrmPyf3ePMnO2+5NMsTZBSeS7GdcUFIRHGoZ'
									.'Lee55U9Q6uszgGT272hbZAMyXWxbmsyAddYeUO6eBdbMSTP31SnqHVKf8A'
									.'k/OXw69an3wKY+qKc9GBHGHEPAtoWyYTT3vzqd/Jiy4k4Nul6bhDEntwUi'
									.'SW5a8yOsjW4wYm1+9ZwiKi58/Sszli2JEjhK7sXQrvZbgzGmyWQZuLMmOT'
									.'0d5Wu46gg40YGOVTvKmPwp2MajwCnYGWnJ6uz1use8z5pNp9M6wYloEEJO'
									.'WGltAHddKedO5iQ5wSx8+meK2pKtkLBsvwtORccNEDm6tSaS0AIrtvhKnb'
									.'xhj0tZUoFAoFAoFAoMKIr1Sgxyw8qByw8qDKCKdEoM0CgUCgUCg//9k%3D'),
									 'image/jpg');

		if($SUMO['DB']->IsConnected())
		{
			$user_image = sumo_get_user_image($_GET['id']);
		}

		if(!$user_image) $user_image = $image;

		header("Content-type: ".$user_image[1]);
		header("Content-Transfer-Encoding: binary");

		echo $user_image[0];

		break;


	case 'GET_USER_REFLECTION':

		//	bgc (the background colour used, defaults to white if not given)
		if(!isset($_GET['bgc']))
		{
			$r = $g = $b = 255;
		}
		else
		{
			//	Extract the hex colour
			//	Does it start with a hash? If so then strip it
			$hex_bgc = str_replace('#', '', $_GET['bgc']);

			switch (strlen($hex_bgc))
			{
				case 6:
					$r = hexdec(substr($hex_bgc, 0, 2));
					$g = hexdec(substr($hex_bgc, 2, 2));
					$b = hexdec(substr($hex_bgc, 4, 2));
					break;

				case 3:
					$r = substr($hex_bgc, 0, 1);
					$g = substr($hex_bgc, 1, 1);
					$b = substr($hex_bgc, 2, 1);
					$r = hexdec($r . $r);
					$g = hexdec($g . $g);
					$b = hexdec($b . $b);
					break;

				default:
					//	Wrong values passed, default to white
					$r = $g = $b = 255;
					break;
			}
		}

		//	height (how tall should the reflection be?)
		if(isset($_GET['height']))
		{
			$out_height = $_GET['height'];

			//	Have they given us a percentage?
			if(substr($out_height, -1) == '%')
			{
				//	Yes, remove the % sign
				$out_height = (int) substr($out_height, 0, -1);
				//	Gotta love auto type casting ;)
				$out_height = $out_height < 10 ? $out_height = "0.0$out_height" : "0.$out_height";
			}
			else
			{
				$out_height = (int) $out_height;
			}
		}
		else
		{
			//	No height was given, so default to 50% of the source images height
			$out_height = 0.50;
		}

		//	offset (starting from an alpha value of around 80 looks cool)
		if (isset($_GET['alpha']))
		{
			$alpha = $_GET['alpha']>127 ? 127 : intval($_GET['alpha']);

			if($alpha < 1) exit('What is the point then of setting an alpha value less than 1?!');
		}
		else
		{
			$alpha = 80;
		}

		//	fade rate (how quickly will the image fade away into the given bg colour?)
		if (isset($_GET['fade']))
		{
			$fade_rate = (int) $_GET['fade'];

			if ($fade_rate < 1)	$fade_rate = 1;
		}
		else
		{
			$fade_rate = 2;
		}

		$protocol     = $_SERVER['HTTPS'] ? "https://" : "http://";
		//$source_image = $protocol.$_SERVER['HTTP_HOST']."/sumo/services.php?module=users&service=image&cmd=GET_USER&id=".intval($_GET['id']);
		$source_image = $protocol.$_SERVER['HTTP_HOST'].str_replace("..", "", $SUMO['page']['web_path'])
						."services.php?module=users&service=image&cmd=GET_USER&id=".intval($_GET['id']);

		//	How big is the image?
		$image_details = getimagesize($source_image);

		if ($image_details === false)
			exit('Not a valid image supplied, or this script does not have permissions to access it.');
		else
		{
			$width  = $image_details[0];
			$height = $image_details[1];
			$type   = $image_details[2];
			$mime   = $image_details['mime'];
		}

		//	Calculate the height of the output image
		//	true:  The output height is a percentage
		//	false: The output height is a fixed pixel value
		$new_height = $output_height < 1 ? $height * $out_height : $out_height;

		//  Detect the source image format - only GIF, JPEG and PNG are supported.
		//  If you need more, extend this yourself.
		switch ($type)
		{
			case 1:
				//	GIF
				$source = imagecreatefromgif($source_image);
				break;

			case 2:
				//	JPG
				$source = imagecreatefromjpeg($source_image);
				break;

			case 3:
				//	PNG
				$source = imagecreatefrompng($source_image);
				break;

			default:
				exit('Unsupported image file format.');
		}

		/**
		 * Build the reflection image
		 */
		//	We'll store the final reflection in $output. $buffer is for internal use.
		$output = imagecreatetruecolor($width, $new_height);
		$buffer = imagecreatetruecolor($width, $new_height);

		//	Copy the bottom-most part of the source image into the output
		imagecopy($output, $source, 0, 0, 0, $height - $new_height, $width, $new_height);

		//	Rotate and flip it (strip flip method)
		for ($y = 0; $y < $new_height; $y++)
		{
			imagecopy($buffer, $output, 0, $new_height-$y-1, 0, $y, $width, 1);
		}

		$output = $buffer;


		/**
		 * Apply the fade effect
		 */
		//	This is quite simple really. There are 127 available levels of alpha, so we just
		//	step-through the reflected image, drawing a box over the top, with a set alpha level.
		//	The end result? A cool fade into the background colour given.
		//	There are a maximum of 127 alpha fade steps we can use, so work out the alpha step rate
		$step = $new_height >= 127 ? ceil($new_height / 127) : ceil(127 / $new_height);

		for ($y = 0; $y <= $new_height; $y = $y + $step)
		{
			imagefilledrectangle($output, 0, $y, $width, $y+$step-1, imagecolorallocatealpha($output, $r, $g, $b, $alpha));

			$alpha = $alpha - $fade_rate;

			if ($alpha < 0) $alpha = 0;
		}

		//	If you'd rather output a JPEG instead of a PNG then
		//  pass the parameter 'jpeg' (no value needed) on the querystring
		if (isset($_GET['jpeg']))
		{
			$quality = (int) $_GET['jpeg'];

			if ($quality < 1 || $quality > 100) $quality = 80;

			//	JPEG (the final parameter = the quality, 0 = terrible, 100 = pixel perfect)
			header("Content-type: image/jpeg");
			imagejpeg($output, '', $quality);
		}
		else
		{
			//	PNG
			header("Content-type: image/png");
			imagepng($output);
		}

		imagedestroy($output);

		break;


	// Unknow command
	default:
		echo "E00121X";
		break;
}

exit;

?>