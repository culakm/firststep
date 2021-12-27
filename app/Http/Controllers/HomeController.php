<?php
/**
 * HomeController Class Doc Comment
 * 
 * PHP Version 8.0.12
 * 
 * @category Class
 * @package  App\Http\Controllers
 * @author   VelkyMongol <velkymongo@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.hellbilling.com/
 */
namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
/**
 * HomeController Class Doc Comment
 * 
 * PHP Version 8.0.12
 * 
 * @category Class
 * @package  App\Http\Controllers
 * @author   VelkyMongol <velkymongo@gmail.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.hellbilling.com/
 */


class HomeController extends Controller
{
    /**
     * Function home
     * 
     * @return view for home URI
     */
    public function home()
    {
        //dd(Auth::user()); // vrati prihlaseneho usera este su metody id(),check() ...
        // template_name
        return view('home.index');
    }
    
    /**
     * Function home
     * 
     * @return view for home URI
     */
    public function contact()
    {
        return view('home.contact');
    }

    /**
     * Function home
     * 
     * @return view for home URI
     */
    public function secret()
    {
        return view('home.secret');
    }
}
