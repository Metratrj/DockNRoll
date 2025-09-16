<?php

namespace Tests\Http;

use App\Http\View;
use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    private string $tempDir;
    private string $tempViewFile;

    protected function setUp(): void
    {
        $this->tempDir = sys_get_temp_dir() . '/view_test';
        if (!is_dir($this->tempDir)) {
            mkdir($this->tempDir);
        }
        $this->tempViewFile = $this->tempDir . '/test_view.php';
        // Create a dummy layout file that includes the view
        file_put_contents($this->tempDir . '/layout.php', 'Layout start | <?php include $view_file; ?> | Layout end');
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tempViewFile)) {
            unlink($this->tempViewFile);
        }
        if (file_exists($this->tempDir . '/layout.php')) {
            unlink($this->tempDir . '/layout.php');
        }
        if (is_dir($this->tempDir)) {
            rmdir($this->tempDir);
        }
    }

    public function testRenderWithLayout()
    {
        file_put_contents($this->tempViewFile, 'Hello, <?php echo $name; ?>!');

        $view = new View($this->tempDir);
        $output = $view->render('test_view', ['name' => 'World']);

        $this->assertEquals('Layout start | Hello, World! | Layout end', $output);
    }

    public function testRenderThrowsExceptionIfViewNotFound()
    {
        $this->expectException(\InvalidArgumentException::class);

        $view = new View($this->tempDir);
        $view->render('non_existent_view');
    }
}
