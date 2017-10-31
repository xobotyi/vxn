<?php
    /**
     * @Author : Anton Zinovyev
     */

    namespace Vxn\Core;

    use Vxn\Helper\Str;

    final class FS
    {
        public static function Read(string $fPath) :string
        {
            return file_get_contents($fPath) ?: '';
        }

        public static function Write(string $fPath, string $contents, bool $append = false) :bool
        {
            if ($append) {
                return file_put_contents($fPath, $contents, FILE_APPEND) !== false;
            }

            self::Delete($fPath);

            return file_put_contents($fPath, $contents) !== false;
        }

        public static function Delete(string $fPath) :bool
        {
            if (file_exists($fPath)) {
                unlink($fPath);
            }

            return true;
        }

        public static function Rename(string $oldName, string $newName, bool $rewriteExistent = false) :bool
        {
            if ($rewriteExistent) {
                self::Delete($newName);
            }

            return rename($oldName, $newName);
        }

        public static function IsDir(string $fPath) :bool
        {
            if (!$fPath) {
                return false;
            }

            return is_dir($fPath);
        }

        public static function MkDir(string $path, $mode = 0777, bool $recursive = true) :bool
        {
            if (is_dir($path)) {
                return true;
            }

            $parent = dirname($path);

            if ($recursive && !is_dir($parent)) {
                self::MkDir($parent, $mode, true);
            }

            $res = mkdir($path, $mode);
            chmod($path, $mode);

            return $res;
        }

        public static function RmDir(string $path, bool $recursive = true) :bool
        {
            $path = rtrim($path, '\\\/ ');
            if (!is_dir($path)) {
                return true;
            }

            if ($recursive) {
                $dirListing = self::ListDir($path);

                foreach ($dirListing['files'] as &$fPath) {
                    self::Delete(Str::PathJoin($path, $fPath));
                }

                foreach ($dirListing['directories'] as &$dPath) {
                    self::RmDir(Str::PathJoin($path, $dPath), false);
                }
            }

            return rmdir($path);
        }

        public static function ListDir(string $path, bool $recursive = true) :array
        {
            $response = [
                'list'        => [],
                'files'       => [],
                'directories' => [],
            ];

            $path = realpath(rtrim($path, '\\ /'));

            if (!is_dir($path)) {
                return $response;
            }


            if ($recursive) {
                $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::FOLLOW_SYMLINKS),
                                                     \RecursiveIteratorIterator::CHILD_FIRST);
                $it->rewind();
                while ($it->valid()) {
                    if (!$it->isDot()) {
                        if ($it->isFile()) {
                            $response['list'][] = $response['files'][] = $it->getSubPathName();
                        }
                        else if ($it->isDir()) {
                            $response['list'][] = $response['directories'][] = $it->getSubPathName();
                        }
                    }

                    $it->next();
                }
            }
            else {
                $it = new \DirectoryIterator($path);

                foreach ($it as $info) {
                    if (!$info->isDot()) {
                        if ($info->isFile()) {
                            $response['list'][] = $response['files'][] = $info->getFilename();
                        }
                        else if ($info->isDir()) {
                            $response['list'][] = $response['directories'][] = $info->getFilename();
                        }
                    }
                }
            }

            unset($it);

            return $response;
        }

        public static function Load(string $fPath, bool $returnResult = false, bool $once = true)
        {
            if (!($fPath = realpath($fPath)) || !self::IsFile($fPath)) {
                return false;
            }

            if ($once) {
                return $returnResult ? include_once $fPath : (bool)include_once $fPath;
            }
            else {
                return $returnResult ? include $fPath : (bool)include $fPath;
            }
        }

        public static function IsFile(string $fPath) :bool
        {
            if (!$fPath) {
                return false;
            }

            return is_file($fPath);
        }
    }