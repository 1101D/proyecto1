<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    public function index(Request $request): View
    {
        $organizations = $request->user()
            ->organizations()
            ->withCount('events')
            ->latest()
            ->get();

        return view('organizations.index', compact('organizations'));
    }

    public function create(): View
    {
        return view('organizations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateOrganization($request);

        $request->user()->organizations()->create([
            ...$data,
            'slug' => $this->uniqueSlug($data['name']),
        ]);

        return redirect()->route('organizations.index')->with('status', 'Organización creada correctamente.');
    }

    public function edit(Organization $organization): View
    {
        $this->authorizeOwner($organization);

        return view('organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization): RedirectResponse
    {
        $this->authorizeOwner($organization);

        $data = $this->validateOrganization($request);

        if ($data['name'] !== $organization->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $organization->id);
        }

        $organization->update($data);

        return redirect()->route('organizations.index')->with('status', 'Organización actualizada correctamente.');
    }

    public function destroy(Organization $organization): RedirectResponse
    {
        $this->authorizeOwner($organization);

        $organization->delete();

        return redirect()->route('organizations.index')->with('status', 'Organización eliminada. Sus eventos siguen activos, sin organización asociada.');
    }

    private function authorizeOwner(Organization $organization): void
    {
        abort_unless($organization->user_id === auth()->id(), 403);
    }

    private function validateOrganization(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (Organization::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
