<h1>Manage plugins</h1>
<div class="table-wrapper">
    <form action="" method="POST">
        <table>
            <tr>
                <th>Plugin Name</th>
                <th>Active</th>
                <th>Description</th>
                <th>Version</th>
            </tr>
            @foreach($availablePlugin as $available => $facade)
            <?php $data = $application->GetPluginMeta($available); ?>
                @unless(in_array($available, $application->GetConstantPlugins()))
                <tr>
                    <td>{{ $data->Name or $available }}</td>
                    <td>
                        <div class="onoffswitch">
                            <input type="hidden" name="plugin[{{ $available }}][action]"
                                   value="{{ (!in_array($available, $installedPlugin) ? 'uninstalled' : '') }}"/>
                            <input type="checkbox" name="plugin[{{ $available }}][value]"
                                   id="myonoffswitch_{{ $available }}" class="onoffswitch-checkbox checkbox-submit"
                                @if(in_array($available, $installedPlugin))
                                       checked="checked"
                                @endif
                            />
                            <label class="onoffswitch-label" for="myonoffswitch_{{ $available }}">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </div>
                    </td>
                    <td>{{ $data->Description or '<em>Unavailable</em>' }}</td>
                    <td>{{ $data->Version or '<em>Unavailable</em>' }}</td>
                </tr>
                @endunless
            @endforeach
        </table>

        <input type="hidden" name="placeholder" value="1"/>
        <button type="submit" name="submit">Submit</button>
    </form>
</div>