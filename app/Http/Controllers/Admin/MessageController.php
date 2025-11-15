<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $messages = Message::latest()->paginate(15);
            return view('admin.messages.index', compact('messages'));
        } catch (Exception $e) {
            Log::error('Failed to load messages index page: ' . $e);
            return redirect()->back()->with('error', 'Could not load messages.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $message = Message::findOrFail($id);
            $message->delete();
            Log::info('Message deleted successfully.', ['id' => $id]);
            return redirect()->route('message.index')
                             ->with('success', 'Message deleted successfully.');
        } catch (Exception $e) {
            Log::error("Failed to delete message ID {$id}: " . $e);
            return redirect()->route('message.index')
                             ->with('error', 'Failed to delete message.');
        }
    }
}