<?
function getPerms($dir) {
  $perms = fileperms($dir);
  // Owner
  $info .= (($perms & 0x0080) ? 'w' : '-');
  // Group
  $info .= (($perms & 0x0010) ? 'w' : '-');
  // World
  $info .= (($perms & 0x0002) ? 'w' : '-');
  return $info;
}

?>