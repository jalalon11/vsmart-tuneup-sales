use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Customer devices endpoint
Route::middleware('auth')->group(function () {
    Route::get('/customers/{customer}/devices', [CustomerController::class, 'devices'])
        ->name('api.customer.devices');
}); 