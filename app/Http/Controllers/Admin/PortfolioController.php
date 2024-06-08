<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PortfolioRequest;
use Illuminate\Http\Request;
use File;
use App\Models\Portfolio;
use PhpParser\Node\Stmt\If_;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::get();
        return view('admin.portfolio.index',['portfolios' => $portfolios]);
    }

    public function create()
    {
        return view('admin.portfolio.create');
    }

    public function store(PortfolioRequest $request)
    {   
        // $image = UploadController::uploadSingleImage('image/portfolio');
        // dd($request->all());
        if($request->hasFile('file')){
            $name_setter = time() .'.'. $request->file('file')->getClientOriginalExtension();
            $photo = $request->file('file')->storeAs('/public/image/portfolio', $name_setter);
        }
        // $request->request->add(['image' => $image]);
        // Portfolio::create($request->all());
        $portfolio = new Portfolio();
        $portfolio->title = $request->title;
        $portfolio->description = $request->description;
        $portfolio->image = $name_setter;
        $susu=$portfolio->save();
        if ($susu) {
            return redirect()->route('portfolio.index')->with('success','Data berhasil ditambah');
        }
        
    }

    public function edit(Portfolio $portfolio)
    {
        return view('admin.portfolio.edit',['portfolio' => $portfolio]);
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $portfolio->update($request->all());
        return redirect()->route('portfolio.index')->with('success','Data berhasil update');
    }

    public function destroy(Portfolio $portfolio)
    {
        File::delete(storage_path('app/public/uploads/image/portfolio/'.$portfolio->image));
        $portfolio->delete();
        return redirect()->route('portfolio.index')->with('success','Data berhasil dihapus');
    }
}
