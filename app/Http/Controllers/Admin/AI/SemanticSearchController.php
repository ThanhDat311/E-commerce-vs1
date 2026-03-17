<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SemanticSearchController extends Controller
{
    /**
     * Display semantic search configuration and synonym management.
     */
    public function index(): View
    {
        $synonyms = Setting::get('search_synonyms', 'ai', []);
        if (! is_array($synonyms)) {
            $synonyms = [];
        }

        // "No results" queries stored as a JSON array in settings
        $noResultsQueries = Setting::get('search_no_results_log', 'ai', []);
        if (! is_array($noResultsQueries)) {
            $noResultsQueries = [];
        }

        return view('pages.admin.ai-semantic-search.index', compact('synonyms', 'noResultsQueries'));
    }

    /**
     * Save a new synonym pair.
     */
    public function addSynonym(Request $request): RedirectResponse
    {
        $request->validate([
            'term' => 'required|string|max:100',
            'synonym' => 'required|string|max:100',
        ]);

        $synonyms = Setting::get('search_synonyms', 'ai', []);
        if (! is_array($synonyms)) {
            $synonyms = [];
        }

        // Add new pair (allow duplicates for term, multiple synonyms)
        $synonyms[] = [
            'term' => strtolower(trim($request->term)),
            'synonym' => strtolower(trim($request->synonym)),
        ];

        Setting::set('search_synonyms', $synonyms, 'ai', 'json');

        return back()->with('success', 'Synonym added successfully.');
    }

    /**
     * Remove a synonym pair by its index.
     */
    public function removeSynonym(Request $request): RedirectResponse
    {
        $request->validate(['index' => 'required|integer|min:0']);

        $synonyms = Setting::get('search_synonyms', 'ai', []);
        if (! is_array($synonyms)) {
            $synonyms = [];
        }

        unset($synonyms[$request->index]);
        Setting::set('search_synonyms', array_values($synonyms), 'ai', 'json');

        return back()->with('success', 'Synonym removed.');
    }
}
