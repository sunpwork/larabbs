<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Link;
use App\Models\User;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Category $category, Request $request, User $user, Link $link)
    {
        $topics = $category->topics()
            ->withOrder($request->order)
            ->paginate();

        $active_users = $user->getActiveUsers();
        $links = $link->getAllCached();
        return view('topics.index', compact('topics', 'category', 'active_users', 'links'));
    }
}
