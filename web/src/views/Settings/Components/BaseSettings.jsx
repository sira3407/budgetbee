import React, { useEffect, useState } from "react";

import Api from "../../../Api/Endpoints";

export default function BaseSettings() {
    const [userSettings, setUserSettings] = useState([]);
    const [currencies, setCurrencies] = useState([]);

    useEffect(() => {
        async function getData() {
            const userSettings = await Api.getUserSettings();
            const currencies = await Api.getCurrencies();
            setUserSettings(userSettings);
            setCurrencies(currencies);
        }
        getData();
    }, []);

    const handleInputChange = (event) => {
        const field = event.target.name;
        const value = event.target.value;
        const data = { [field]: value };
        setUserSettings((prevData) => ({ ...prevData, ...data }));
    }

    const handleSaveSettings = async () => {
        Api.updateUserSettings(userSettings);
    }

    return (
        <div className="mt-20 px-5 m-auto relative text-white">
            <h3 className="text-3xl font-bold mb-4">Main settings</h3>
            <div className="flex flex-col gap-y-10">
                <div>
                    <label
                        htmlFor="name"
                        className="block mb-2 text-sm font-medium text-gray-900 text-white"
                    >
                        Base currency
                    </label>
                    <select
                        name="currency_id"
                        id="currency_id"
                        required="required"
                        onChange={handleInputChange}
                        className="block w-full p-4 border border-gray-700 rounded-lg bg-background sm:text-md focus:ring-blue-500 focus:border-blue-500"
                    >
                        {currencies.map((currency, index) => {
                            return (
                                <option
                                    key={index}
                                    value={currency.id}
                                    selected={
                                        currency.id ===
                                        userSettings?.currency.id
                                    }
                                >
                                    {currency.name} {currency.symbol} (
                                    {currency.code})
                                </option>
                            );
                        })}
                    </select>
                </div>
                <div>
                    <button
                        type="submit"
                        onClick={() => handleSaveSettings({})}
                        className="flex flex-row w-full px-5 py-3 gap-x-5 bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 focus:ring-green-800 shadow-lg shadow-green-500/50 shadow-lg shadow-green-800/80 rounded-full justify-center cursor-pointer items-center transition"
                    >
                        Save
                    </button>
                </div>
            </div>
        </div>
    );
}