<?php

function get_hash_password($id, $pass) {
	return md5(md5($id.$pass.'HFDjkljsf89fusfl').'GH67YGJFytiKHJ');
}

?>