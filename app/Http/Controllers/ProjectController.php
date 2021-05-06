<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Models\Tag;
use App\Models\Project;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Instantiate a new ProjectController instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get a project
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, Project $project): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            data: new ProjectResource($project)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'max:255'],
            'description' => ['required ', 'min:10', 'max:200'],
            'collaborators' => ['array', 'exists:users,id'],
            'badges' => ['array', 'exists:technologies,id'],
            'tags' => ['array'],
            'status' => ['required'],
            'start_date' => ['nullable', 'date'],
            'finished_date' => ['nullable', 'date'],
        ]);

        $project = new Project;
        $project->name = $validatedData['name'];
        $project->description = $validatedData['description'];
        $project->status = $validatedData['status'];
        $project->started_at = $validatedData['start_date'];
        $project->finished_at = $validatedData['finished_date'];
        $project->save();

        if ($request->exists('collaborators')) {
            $project->members()->attach($validatedData['collaborators'], ['role' => config('permissions.names')[-1]]);
            $project->members()->attach(Auth::user()->id, ['role' => config('permissions.names')[0]]);
        }

        if ($request->exists('badges')) {
            $project->technologies()->attach($validatedData['badges']);
        }

        // Tags

        if ($request->exists('tags')) {
            $existingTags = Tag::find($validatedData['tags']);
            $tags = $existingTags->map(function ($item) {
                return $item->name;
            });

            $missingTags = collect($validatedData['tags'])->diff($tags);
            foreach ($missingTags as $key => $value) {
                $tags->push(Tag::create(['name' => $value])->name);
            }
            $project->tags()->attach($tags);
        }

        return redirect()->route('projects.show', ['project' => $project->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project): \Illuminate\Http\Response
    {
        //
    }

    /**
     * Search a list of project
     */
    public function search(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = $request->query('query');
        $projects = Project::where('name', 'like', '%'.$query.'%')
                            ->limit(3)->get();
        return response()->json(
            data: ProjectResource::collection($projects)
        );
    }

    /**
     * Get the posts of the project
     */
    public function posts(Request $request, Project $project): PostCollection
    {
        return new PostCollection($project->posts()->paginate(5));
    }
}
