<?php

namespace App\Livewire\Admin\Events;

use App\Domain\Event\Models\Event;
use App\Support\Enums\EventFormat;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class EventManager extends Component
{
    use WithPagination;

    public $title, $description, $content, $format, $location, $quota, $price, $is_free = true;
    public $registration_start, $registration_end, $event_start, $event_end, $status = 'draft';
    public $activeEventId;
    public $showModal = false;
    public $noticeMessage = '';

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'format' => 'required',
            'event_start' => 'required|date',
            'event_end' => 'required|date|after_or_equal:event_start',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after_or_equal:registration_start',
            'quota' => 'nullable|integer|min:1',
        ];
    }

    public function create()
    {
        $this->reset(['title', 'description', 'content', 'format', 'location', 'quota', 'price', 'is_free', 'registration_start', 'registration_end', 'event_start', 'event_end', 'status', 'activeEventId']);
        $this->showModal = true;
    }

    public function edit(int $id)
    {
        $event = Event::findOrFail($id);
        $this->activeEventId = $event->id;
        $this->title = $event->title;
        $this->description = $event->description;
        $this->content = $event->content;
        $this->format = $event->format->value ?? $event->format;
        $this->location = $event->location;
        $this->quota = $event->quota;
        $this->price = $event->price;
        $this->is_free = $event->is_free;
        $this->registration_start = $event->registration_start?->format('Y-m-d\TH:i');
        $this->registration_end = $event->registration_end?->format('Y-m-d\TH:i');
        $this->event_start = $event->event_start?->format('Y-m-d\TH:i');
        $this->event_end = $event->event_end?->format('Y-m-d\TH:i');
        $this->status = $event->status;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'description' => $this->description,
            'content' => $this->content,
            'format' => $this->format,
            'location' => $this->location,
            'quota' => $this->quota,
            'price' => $this->is_free ? 0 : $this->price,
            'is_free' => $this->is_free,
            'registration_start' => $this->registration_start,
            'registration_end' => $this->registration_end,
            'event_start' => $this->event_start,
            'event_end' => $this->event_end,
            'status' => $this->status,
            'created_by' => auth()->id(),
        ];

        if ($this->activeEventId) {
            Event::find($this->activeEventId)->update($data);
            $this->noticeMessage = 'Event berhasil diperbarui!';
        } else {
            Event::create($data);
            $this->noticeMessage = 'Event baru berhasil dibuat!';
        }

        $this->showModal = false;
    }

    public function delete(int $id)
    {
        Event::find($id)?->delete();
        $this->noticeMessage = 'Event dihapus.';
    }

    public function render()
    {
        $events = Event::withCount('registrations')->orderBy('event_start', 'desc')->paginate(10);
        
        return view('livewire.admin.events.event-manager', [
            'events' => $events,
            'formatOptions' => EventFormat::cases(),
        ]);
    }
}
