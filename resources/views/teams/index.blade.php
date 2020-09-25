<!DOCTYPE html>

<html>
    <head>
        <title>Teams</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <link href="https://unpkg.com/tailwindcss@^1.8/dist/tailwind.min.css" rel="stylesheet">
    </head>

    <body class="antialiased">
        <div class="min-h-screen bg-gradient-to-t from-indigo-100 via-white to-white p-16">
            <p class="text-center lg:text-left text-2xl lg:text-3xl tracking-tight font-extrabold text-gray-700 mb-4">
                Teams
            </p>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
                @foreach($teams as $team)
                    <div class="md:col-span-12 lg:col-span-6 xl:col-span-3 bg-white rounded-md shadow">
                        <div class="px-6 py-4 bg-teal-800 rounded-t-md flex justify-between items-center">
                            <div>
                                <p class="text-white font-semibold leading-5">
                                    {{ $team['name'] }}
                                </p>

                                <p class="text-white font-medium italic text-sm">
                                    Average rank: {{ $team['roster']['average_ranking'] }}
                                </p>
                            </div>

                            <div class="h-12 w-12 text-sm rounded-full bg-white flex items-center justify-center text-teal-800 font-bold">
                                {{ $team['roster']['total_ranking'] }}
                            </div>
                        </div>

                        <div class="overflow-y-auto rounded-b-md" style="max-height: 32rem;">
                            @foreach($team['roster']['players'] as $rosterSpot)
                                <div class="flex items-center justify-between hover:bg-gray-100 cursor-pointer transition duration-200">
                                    <div class="relative px-2 py-2">
                                        <span class="bg-gray-800 text-gray-400 font-bold flex items-center justify-center absolute left-0 top-0 w-10 h-full text-xs">
                                            {{ $loop->iteration }}
                                        </span>

                                        <div class="pl-12">
                                            <p class="text-gray-700 font-semibold flex-1 leading-5">
                                                {{ $rosterSpot['player']['first_name']  }} {{ $rosterSpot['player']['last_name']  }}
                                            </p>

                                            <p class="text-gray-600 italic text-sm">
                                                Rank: {{ $rosterSpot['player']['ranking'] }}
                                            </p>
                                        </div>
                                    </div>

                                    @if($rosterSpot['is_goalie'])
                                        <span class="bg-indigo-100 text-indigo-700 rounded-md font-bold flex-shrink-0 text-xs px-3 py-1 mx-4">
                                            Goalie
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </body>
</html>
