<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getDashboardData()
    {
        // Get counts for users, articles, and others
        $userCount = User::count();
        $beritaCount = Article::where('role', 'berita')->count();
        $jurnalCount = Article::where('role', 'jurnal')->count();
        $bukuCount = Article::where('role', 'majalah')->count();

        // Get daily user counts
        $dailyUserCounts = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Get daily article counts for berita, jurnal, and majalah
        $dailyBeritaCounts = Article::where('role', 'berita')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $dailyJurnalCounts = Article::where('role', 'jurnal')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $dailyBukuCounts = Article::where('role', 'majalah')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Format the data for frontend consumption
        $dates = [];
        $userCounts = [];
        $beritaCounts = [];
        $jurnalCounts = [];
        $bukuCounts = [];

        foreach ($dailyUserCounts as $item) {
            $dates[] = Carbon::parse($item->date)->format('Y-m-d'); // Format date
            $userCounts[] = $item->count;
        }

        foreach ($dailyBeritaCounts as $item) {
            $beritaCounts[] = $item->count;
        }

        foreach ($dailyJurnalCounts as $item) {
            $jurnalCounts[] = $item->count;
        }

        foreach ($dailyBukuCounts as $item) {
            $bukuCounts[] = $item->count;
        }

        // Return the data as JSON
        return response()->json([
            'userCount' => $userCount,
            'beritaCount' => $beritaCount,
            'jurnalCount' => $jurnalCount,
            'bukuCount' => $bukuCount,
            'dailyUserCounts' => [
                'labels' => $dates,
                'data' => $userCounts
            ],
            'dailyArticleCounts' => [
                'berita' => [
                    'labels' => $dates,
                    'data' => $beritaCounts
                ],
                'jurnal' => [
                    'labels' => $dates,
                    'data' => $jurnalCounts
                ],
                'buku' => [
                    'labels' => $dates,
                    'data' => $bukuCounts
                ]
            ]
        ]);
    }
}
