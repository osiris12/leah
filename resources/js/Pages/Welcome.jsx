import TextInput from "@/Components/TextInput.jsx";

export default function Welcome({words}) {
    console.log(words);
    return (
        <>
            <div className="w-full mb-48"></div>
            <div className="flex justify-end items-center bg-gray-800">
                <TextInput className="text-2xl bg-gray-900" dir="rtl"></TextInput>
            </div>
        </>
    );
}
