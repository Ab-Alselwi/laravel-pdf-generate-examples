<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use HeadlessChromium\BrowserFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use Spiritix\HtmlToPdf\Converter;
use Spiritix\HtmlToPdf\Input\UrlInput;
use Spiritix\HtmlToPdf\Output\DownloadOutput;

use dawood\phpChrome\Chrome; 

use Illuminate\Support\Facades\Redis;

class HomeController extends Controller
{
   
   public function index(){

        
      $uri = 'https://google.com';
   
        echo "test generate pdf by chrome ";
          //$chrome=new Chrome('https://droub.net','chrome');
        $browserFactory = new BrowserFactory('chrome');

        // starts headless chrome
        $browser = $browserFactory->createBrowser([
                //    'headless'        => false,          // disable headless mode
                ]);

        try {
            // creates a new page and navigate to an url
            $page = $browser->createPage();
          // $page->navigate('https://droub.net')->waitForNavigation();
           // navigate
            $navigation = $page->navigate($uri);

            // wait for the page to be loaded
            $navigation->waitForNavigation();

            // take a screenshot
            $screenshot = $page->screenshot([
                'format'  => 'jpeg',  // default to 'png' - possible values: 'png', 'jpeg',
                'quality' => 80,      // only if format is 'jpeg' - default 100
            ]);

            // save the screenshot
            $screenshot->saveToFile(time().'-files.jpg');
            // get page title
            $pageTitle = $page->evaluate('document.title')->getReturnValue();
            //dd($pageTitle);
            // screenshot - Say "Cheese"! 😄
            $page->screenshot()->saveToFile(time().'bars.png');

            // pdf
            $page->pdf()->saveToFile(time().'-files_basr.pdf');
           // $page->pdf(['printBackground' => false])->saveToFile('files_basr.pdf');
        } finally {
            // bye
           $browser->close();
        }
         echo "<br>  done <br> we have finished , you will find your files in public folder 😄";
        /* 
        //chrome with proxy
        $innerHTML = null;
        $browserFactory = new BrowserFactory();
        $browser = $browserFactory->createBrowser([
          //  'headless' => false,
          //   'connectionDelay' => 0.8,            // add 0.8 second of delay between each instruction sent to chrome,
         //   'debugLogger'     => 'php://stdout', // will enable verbose mode
           // 'noSandbox' => true,
          //  'startupTimeout' => 180,
         //   'customFlags' => ['--proxy-server="http://proxyip:myproxyport"'],
        ]);
        $page = $browser->createPage();
        $page->navigate($uri);//->waitForNavigation( 180000);
        $innerHTML = $page->evaluate('document.documentElement.innerHTML')->getReturnValue();
        $url = $page->evaluate('document.location.href')->getReturnValue();
        \Log::info($url);
        $browser->close();
        return $innerHTML;*/
    }

    public function runProsses(){
        $html = '<html><body>Test to my awesome PDF 😄</body></html>';

        $descriptors = [
            0 => ['pipe', 'r'],  // we will write to stdin
            1 => ['pipe', 'w'],  // we will read from stdout
            2 => ['pipe', 'w'],  // we will also read from stderr
        ];

        // this array will contain three pointers to all three pipes
        $pipes = [];

        // we're starting the process now
        $process = proc_open('wkhtmltopdf - -', $descriptors, $pipes);
        if (is_resource($process)) {
            // the process has been opened, we can send input data
            fwrite($pipes[0], $html);

            // you have to close the stream after use
            fclose($pipes[0]);

            // now we're reading binary output
            // PHP will wait until the stream is complete
            $pdf = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            echo "done 😄 download your pdf ";
            $errors = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            // all pipes must be closed now to avoid a deadlock
            $exitCode = proc_close($process);
            return response()->streamDownload(function () use (&$pdf) {
                    echo $pdf;
                }, 'My awesome PDF.pdf', [
                    'Content-Type' => 'application/pdf',
                ]);
          //   return $pdf->download('droub.pdf');
        }

    }

    public function symfonyProcess(){
       //wkhtmltopdf http://google.com google.pdf 
            $process = new Process(['wkhtmltopdf', 'https://droub.net', 'droub.pdf']);
        // $process = new Process(['wkhtmltopdf', '-', '-']);
      //   $html="test";
        //$process->setInput($html);

        try {
            // wait for process execution
            $process->mustRun();

              $pdf = $process->getOutput();
             echo "we generated awesome PDF by symfonyProcess look at public folder 😄 ";
             /* return response()->streamDownload(function () use (&$pdf) {
                    echo $pdf;
                }, 'My awesome PDF by symfonyProcess.pdf', [
                    'Content-Type' => 'application/pdf',
                ]);*/
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
        }
   }
   
    public function chromePdf($url='https://github.com'){

        $browserFactory = new BrowserFactory('chrome');

        // starts headless chrome
        //$browser = $browserFactory->createBrowser();
        // starts headless chrome
        $browser = $browserFactory->createBrowser([
                   // 'headless'        => false,          // disable headless mode
                ]);
    try {
         $page = $browser->createPage();
        // navigate
        $navigation = $page->navigate($url);

        // wait for the page to be loaded
        $navigation->waitForNavigation();

        $options = [
           // 'landscape'           => true,             // default to false
            'printBackground'     => true,             // default to false
            'displayHeaderFooter' => true,             // default to false
            'preferCSSPageSize'   => true,             // default to false ( reads parameters directly from @page )
            'marginTop'           => 0.0,              // defaults to ~0.4 (must be float, value in inches)
            'marginBottom'        => 1.4,              // defaults to ~0.4 (must be float, value in inches)
            'marginLeft'          => 5.0,              // defaults to ~0.4 (must be float, value in inches)
            'marginRight'         => 1.0,              // defaults to ~0.4 (must be float, value in inches)
            'paperWidth'          => 6.0,              // defaults to 8.5 (must be float, value in inches)
            'paperHeight'         => 6.0,              // defaults to 8.5 (must be float, value in inches)
            'headerTemplate'      => '<div>foo</div>', // see details above
            'footerTemplate'      => '<div>foo</div>', // see details above
            'scale'               => 1.2,              // defaults to 1.0 (must be float)
        ];

        // print as pdf (in memory binaries)
        $pdf = $page->pdf($options);
      //  $pdf = $page->pdf();
        /*header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename=filename.pdf');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');*/

        //echo base64_decode($pdf->getBase64());
                // save the pdf
                $pdf->saveToFile(time().'file.pdf');
                // or directly output pdf without saving
         } finally {
            // bye
           $browser->close();
        }

    }

     //
   public function phpchromepdf(){
            $chrome=new Chrome('https://droub.net','chrome');
            $arguments = [
            '--headless' => '',
            '--disable-gpu' => '',
            '--hide-scrollbars' => '',
            '----timeout=' => '6000',
        ];
            $chrome->setArguments($arguments);
            $chrome->setOutputDirectory(public_path());
            //not necessary to set window size
            $chrome->setWindowSize($width=1477,$height=768);
            print "Pdf successfully generated :".$chrome->getPdf().PHP_EOL;
        
            return;
    }

    public function httpCurl(){
        $html="test";
            $response = \Http::post('http://pdfrenderer:8082/render', [
            'html' => $html ,//view('invoice')->render(), // Render a Blade view
        ]);

        $pdf = $response->body();

        // Save the PDF file in storage...
        Storage::put('pdf', $pdf);

        // ...or download it from a Controller
        return response()->streamDownload(function () use (&$pdf) {
            echo $pdf;
        }, 'My PDF.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    

     

    public function htmlTopdf(){
        /*$input = new UrlInput();
        $input->setUrl('https://droub.net');

        $converter = new Converter($input, new DownloadOutput());

        $converter->setOption('landscape', true);

        $converter->setOptions([
            'printBackground' => true,
            'displayHeaderFooter' => true,
            'headerTemplate' => '<p>I am a header</p>',
        ]);

        $output = $converter->convert();
       return $output->download('droub.pdf');*/

        $input = new UrlInput();
            $input->setUrl('https://www.google.com');

            $converter = new Converter($input, new DownloadOutput());

            $converter->setOption('n');
            $converter->setOption('d', '300');

            $converter->setOptions([
                'no-background',
                'margin-bottom' => '100',
                'margin-top' => '100',
            ]);

            $output = $converter->convert();
            $output->download();
    }

    

    public function test(){
        // open browser
        $factory = new BrowserFactory();
        $browser = $factory->createBrowser();

        // navigate to a page with a form
        $page = $browser->createPage();
        $page->navigate('file://'.__DIR__.'/html/form.html')->waitForNavigation();

        // put 'hello' in the input and submit the form
        $evaluation = $page->evaluate(
        '(() => {
                document.querySelector("#myinput").value = "hello";
                document.querySelector("#myform").submit();
            })()'
        );

        // wait for the page to be reloaded
        $evaluation->waitForPageReload();

        // get value in the new page
        $value = $page->evaluate('document.querySelector("#value").innerHTML')->getReturnValue();
      }

         
    }

