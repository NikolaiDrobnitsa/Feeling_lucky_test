<?php

namespace App\Http\Controllers;

use App\Models\GameResult;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use App\Models\UniqueLink;
class UniqueLinkController extends Controller
{


    public function generateLinkPage()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $uniqueLink = Str::random(12);
        if ($user->uniqueLinks->count() > 0) {
            $user->uniqueLinks->first()->update(['link' => $uniqueLink, 'created_at' => Carbon::now()]);
        } else {
            $user->uniqueLinks()->create(['link' => $uniqueLink, 'created_at' => Carbon::now()]);
        }

        return redirect('/generate-link/' . $uniqueLink);
    }

    public function generateUniqueLink(Request $request)
    {
        $user = Auth::user();

        $uniqueLink = Str::random(12);
        $user->uniqueLinks()->create([
            'link' => $uniqueLink,
            'created_at' => Date::now(),
        ]);

        return redirect('/generate-link/' . $uniqueLink);
    }







    public function showGeneratedLinkPage($uniqueLink)
    {
        $link = UniqueLink::where('link', $uniqueLink)->first();

        if (!$link) {

            return redirect('/process-registration');
        }

        $now = Date::now();
        $expirationDate = $link->created_at->addDays(7);
        if ($now > $expirationDate) {

            return view('expiration-link', compact('uniqueLink','expirationDate'));
        }

        return view('generate-link', compact('uniqueLink','expirationDate'));
    }

    public function showMainPage()
    {
        $uniqueLink = session('uniqueLink');

        return view('main', compact('uniqueLink'));
    }
    public function deactivateLink($uniqueLink)
    {
        $user = Auth::user();
        $link = $user->uniqueLinks()->where('link', $uniqueLink)->first();

        if ($link) {
            $link->delete();
            return redirect()->route('process-registration')->with('status', 'Your link has been deactivated.');
        }

        return redirect()->route('process-registration')->with('status', 'Link not found.');
    }


}
