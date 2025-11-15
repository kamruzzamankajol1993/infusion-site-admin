<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExtraPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class ExtraPageController extends Controller
{
    /**
     * Helper function to get the cached page content.
     * This avoids hitting the DB for every single page request.
     */
    private function getPageContent()
    {
        // Cache the content for 1 hour (3600 seconds).
        // On the admin side, you should clear this cache after updating.
        return Cache::remember('extra_page_content', 3600, function () {
            // Fetch the first (and presumably only) row of page content.
            return ExtraPage::first();
        });
    }

    /**
     * Get the Privacy Policy content.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function privacyPolicy(): JsonResponse
    {
        try {
            $content = $this->getPageContent();
            
            if (!$content || !$content->privacy_policy) {
                return response()->json(['error' => 'Privacy Policy content not found.'], 404);
            }

            return response()->json([
                'data' => $content->privacy_policy
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch Privacy Policy: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve content.'], 500);
        }
    }

    /**
     * Get the Terms & Conditions content.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function terms(): JsonResponse
    {
        try {
            $content = $this->getPageContent();
            
            if (!$content || !$content->term_condition) {
                return response()->json(['error' => 'Terms & Conditions content not found.'], 404);
            }

            return response()->json([
                'data' => $content->term_condition
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch Terms & Conditions: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve content.'], 500);
        }
    }

    /**
     * Get the FAQ content.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function faq(): JsonResponse
    {
        try {
            $content = $this->getPageContent();
            
            if (!$content || !$content->faq) {
                return response()->json(['error' => 'FAQ content not found.'], 404);
            }

            return response()->json([
                'data' => $content->faq
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch FAQ: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve content.'], 500);
        }
    }

   
}
