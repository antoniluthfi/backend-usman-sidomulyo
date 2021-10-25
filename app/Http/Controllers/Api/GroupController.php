<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\UserGroup;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'result' => Group::all()
        ], 200);
    }

    public function getDataByUserId($user_id)
    {
        $group = Group::with('userGroups')->get();
        foreach ($group as $key => $val) {
            $terdaftar = UserGroup::where('group_id', $val->id)
                ->where('user_id', $user_id)
                ->first();
            $admin_pembuat = UserGroup::with('user')
                ->where('group_id', $val->id)
                ->where('hak_akses', 'admin pembuat')
                ->first();
            $group[$key]->terdaftar = $terdaftar ? true : false;
            $group[$key]->nomorhp = $admin_pembuat->user->nomorhp;
        }

        return response()->json([
            'success' => true,
            'result' => $group,
        ], 200);
    }

    public function create(Request $request)
    {
        Group::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'jumlah_anggota' => $request->jumlah_anggota,
            'foto_profil' => $request->foto_profil
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan'
        ], 200);
    }

    public function update(Request $request, $id)
    {
    }
}
